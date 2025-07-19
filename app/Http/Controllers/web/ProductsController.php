<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller {
    
    public function index(Request $request){
        $query = Product::query();
        $categories = Category::all();
        $selectedCategory = null;
        
        // Filter by search query
        if ($request->has('query') && !empty($request->input('query'))) {
            $searchQuery = $request->input('query');
            $query->where(function($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $searchQuery . '%')
                  ->orWhere('code', 'like', '%' . $searchQuery . '%');
            });
        }
        
        // Filter by category
        if ($request->has('category_id') && !empty($request->input('category_id'))) {
            $categoryId = $request->input('category_id');
            $selectedCategory = Category::find($categoryId);
            
            if ($selectedCategory) {
                $query->whereHas('categories', function($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }
        }
        
        // Apply sorting if specified
        if ($request->has('sort') && !empty($request->input('sort'))) {
            $sortField = $request->input('sort');
            $sortDirection = $request->input('direction', 'asc');
            
            switch ($sortField) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', $sortDirection);
                    break;
                default:
                    // Default sorting
                    $query->orderBy('id', 'desc');
            }
        } else {
            // Default sorting
            $query->orderBy('id', 'desc');
        }
        
        $products = $query->paginate(12);
        
        return view('products.list', compact('products', 'categories', 'selectedCategory'));
    }

    public function category(){
        return view('products.category');
    }

    public function ListByCategory($category , Request $request){
        // Find the category by slug
        $categoryModel = Category::where('slug', $category)->firstOrFail();
        
        // Get products from this category
        $query = $categoryModel->products();
        
        $query->when($request->input('keywords'),
            fn($q)=> $q->where("name","like","%".$request->input('keywords')."%"));
        $query->when($request->input('min_price'),
            fn($q)=> $q->where("price", ">=", $request->input('min_price')));
        $query->when($request->input('max_price'),
            fn($q)=> $q->where("price", "<=", $request->input('max_price')));
        $query->when($request->input('order_by'),
            fn($q)=> $q->orderBy($request->input('order_by'), $request->input('order_direction', 'ASC')));
            
        $products = $query->get();
        return view('products.list', compact('products', 'category', 'categoryModel'));
    }

    public function productDetails($id){
        // Find the product with its relationships
        $product = Product::with(['categories', 'colors', 'sizes', 'images', 'ratings', 'recommendedProducts.categories', 'recommendedProducts.colors', 'recommendedProducts.sizes', 'recommendedProducts.images'])->find($id);
        
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        
        // If the product has no recommendations, find similar products
        if ($product->recommendedProducts->count() === 0) {
            // Get similar products from the same category, limited to 3
            $similarProducts = Product::with(['categories', 'colors', 'sizes', 'images'])
                ->whereHas('categories', function($query) use ($product) {
                    $query->whereIn('categories.id', $product->categories->pluck('id'));
                })
                ->where('id', '!=', $product->id) // Exclude current product
                ->inRandomOrder()
                ->limit(3)
                ->get();
            
            // Assign similar products as recommended
            $product->setRelation('recommendedProducts', $similarProducts);
        }
        
        return view('products.details', compact('product'));
    }

    public function manage() {
        if(!Auth::check()) return redirect('login');

        $products = Product::with(['colors', 'sizes', 'categories'])->get();
        return view('products.manage', compact('products'));
    }

    public function edit(Request $request, ?Product $product = null) {
        if(!Auth::check()) return redirect('login');

        $product = $product ?? new Product();
        $colors = Color::all();
        $sizes = Size::all();
        $categories = Category::all();
        
        // If this is an admin route, use admin layout
        if ($request->is('admin/*')) {
            return view('admin.products.edit', compact('product', 'colors', 'sizes', 'categories'));
        }
        
        return view("products.edit", compact('product', 'colors', 'sizes', 'categories'));
    }

    public function save(Request $request, ?Product $product = null)
    {
        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string', 'max:1024'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required','integer','min:0'],
            'photo' => ['nullable', 'image', 'max:2048'], // 2MB max
            'categories' => ['nullable', 'array'],
            'categories.*' => ['nullable', 'exists:categories,id'],
            'main_category' => ['nullable', 'exists:categories,id'],
            'clothing_category' => ['nullable', 'exists:categories,id'],
            'item_category' => ['nullable', 'exists:categories,id'],
        ]);

        $product = $product ?? new Product();
        
        $product->code = $request->code;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->created_by = Auth::id();
        
        // Handle image upload with optimization
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            try {
                // Delete old image if exists
                if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                    Storage::disk('public')->delete($product->photo);
                }
                
                // Get the uploaded file
                $image = $request->file('photo');
                
                // Generate a unique filename
                $filename = time() . '_' . Str::slug($product->name) . '.' . $image->getClientOriginalExtension();
                
                // Create the full path
                $path = 'products/' . $filename;
                
                // Process and optimize the image without requiring intervention/image
                $img = $image->storeAs('products', $filename, 'public');
                
                // Save the path to the product
                $product->photo = $path;
                
                // For backward compatibility, also store in image_path
                $product->image_path = $path;
            } catch (\Exception $e) {
                // Log the error but don't fail the save operation
                Log::error('Product image upload failed: ' . $e->getMessage());
            }
        }
        
        $product->save();

        // Collect all selected categories
        $selectedCategories = [];
        
        // Add individual category selections if they exist
        if ($request->main_category) {
            $selectedCategories[] = $request->main_category;
        }
        
        if ($request->clothing_category) {
            $selectedCategories[] = $request->clothing_category;
        }
        
        if ($request->item_category) {
            $selectedCategories[] = $request->item_category;
        }
        
        // If categories_combined is provided (from JS), use those values
        if ($request->has('categories') && is_array($request->categories) && count($request->categories) > 0) {
            foreach ($request->categories as $categoryId) {
                if (empty($categoryId)) continue;
                
                // Check if the value contains a comma (e.g. "2,6")
                if (strpos($categoryId, ',') !== false) {
                    $parts = explode(',', $categoryId);
                    foreach ($parts as $part) {
                        $part = trim($part);
                        if (!empty($part) && !in_array($part, $selectedCategories)) {
                            $selectedCategories[] = $part;
                        }
                    }
                } else if (!in_array($categoryId, $selectedCategories)) {
                    $selectedCategories[] = $categoryId;
                }
            }
        } else if ($request->has('categories_combined') && !empty($request->categories_combined)) {
            $combinedCategories = explode(',', $request->categories_combined);
            foreach ($combinedCategories as $categoryId) {
                if ($categoryId && !in_array($categoryId, $selectedCategories)) {
                    $selectedCategories[] = $categoryId;
                }
            }
        }

        // Sync relationships
        $product->categories()->sync($selectedCategories);
        $product->colors()->sync($request->colors ?? []);
        $product->sizes()->sync($request->sizes ?? []);

        if ($request->is('admin/*')) {
            return redirect()->route('admin.products.list')->with('success', 'Product saved successfully!');
        }

        return redirect()->route('products.manage')->with('success', 'Product saved successfully!');
    }

    public function delete(Request $request, Product $product) {
        if(!Auth::check()) return redirect('login');
        
        // Delete the product image if it exists
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }
        
        $product->delete();
        
        if ($request->is('admin/*')) {
            return redirect()->route('admin.products.list')->with('success', 'Product deleted successfully!');
        }
        
        return redirect()->route('products.manage')->with('success', 'Product deleted successfully!');
    }
    
    /**
     * Search products based on a query string
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category_id');
        $sort = $request->input('sort');
        $products = [];
        $categories = Category::all();
        $selectedCategory = null;
        
        $productsQuery = Product::query();
        
        // Apply search query if provided
        if (!empty($query)) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            });
        }
        
        // Apply category filter if provided
        if (!empty($categoryId)) {
            $selectedCategory = Category::find($categoryId);
            
            if ($selectedCategory) {
                $productsQuery->whereHas('categories', function($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }
        }
        
        // Apply sorting if specified
        if (!empty($sort)) {
            switch ($sort) {
                case 'price_low':
                    $productsQuery->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $productsQuery->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $productsQuery->orderBy('created_at', 'desc');
                    break;
                case 'name':
                    $productsQuery->orderBy('name', 'asc');
                    break;
                default:
                    // Default sorting
                    $productsQuery->orderBy('id', 'desc');
            }
        } else {
            // Default sorting
            $productsQuery->orderBy('id', 'desc');
        }
        
        $products = $productsQuery->paginate(12);
        
        return view('products.list', compact('products', 'query', 'categories', 'selectedCategory'));
    }
}
