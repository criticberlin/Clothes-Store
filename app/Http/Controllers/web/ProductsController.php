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
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller {
    
    public function index(Request $request){
        $query = Product::query();
        
        if ($request->has('query') && !empty($request->input('query'))) {
            $searchQuery = $request->input('query');
            $query->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $searchQuery . '%');
        }
        
        $products = $query->paginate(12);
        return view('products.list', compact('products'));
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
        $product = Product::with(['categories', 'colors', 'sizes'])->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
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

    public function save(Request $request, ?Product $product = null) {
        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string', 'max:1024'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required','integer','min:0'],
            'photo' => ['nullable', 'image', 'max:2048'], // 2MB max
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id']
        ]);

        $product = $product ?? new Product();
        
        $product->code = $request->code;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->created_by = Auth::id();
        
        // Handle image upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old image if exists
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }
            
            $imagePath = $request->file('photo')->store('products', 'public');
            $product->photo = $imagePath;
        }
        
        $product->save();

        // Sync relationships
        $product->categories()->sync($request->categories);
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
}
