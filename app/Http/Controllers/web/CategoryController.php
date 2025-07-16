<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        // Get all categories with their relationships
        $categories = Category::with(['parent', 'parents', 'children', 'products'])
            ->orderBy('name')
            ->paginate(15);
            
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $category = new Category();
        // Get all categories that could potentially be parents
        $parentCategories = Category::orderBy('name')->get();
        $categoryTypes = $this->getCategoryTypes();
        
        return view('admin.categories.create', compact('category', 'parentCategories', 'categoryTypes'));
    }

    /**
     * Show the form for editing a category
     */
    public function edit(Category $category)
    {
        // Eager load parents relationship
        $category->load('parents');
        
        // Get all possible parent categories (excluding self and descendants)
        $parentCategories = $category->getAvailableParents();
        $categoryTypes = $this->getCategoryTypes();
        
        return view('admin.categories.edit', compact('category', 'parentCategories', 'categoryTypes'));
    }

    /**
     * Store a new category
     */
    public function store(Request $request)
    {
        // Debug logging
        Log::info('Category store method called', ['request' => $request->all()]);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|array',
            'parent_id.*' => 'exists:categories,id',
            'type' => 'required|in:main,clothing,item_type',
            'slug' => 'nullable|string|unique:categories,slug',
            'photo' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ]);

        // Debug parent_id values
        Log::info('Parent IDs before processing', ['parent_id' => $request->parent_id]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->type = $validated['type'];
        $category->slug = $validated['slug'] ?? Str::slug($validated['name']);
        $category->description = $validated['description'] ?? null;
        $category->status = $request->has('status') ? true : false;

        // Process parent_id array - ensure it's an array even if a single value is submitted
        $parentIds = [];
        if ($request->has('parent_id')) {
            if (is_array($request->parent_id)) {
                $parentIds = $request->parent_id;
            } else {
                $parentIds = [$request->parent_id];
            }
            
            // Filter out any empty values
            $parentIds = array_filter($parentIds, function($id) {
                return !empty($id);
            });
            
            Log::info('Parent IDs after filtering', ['parentIds' => $parentIds]);
        }

        // Validate hierarchy based on type
        $hierarchyError = $this->validateHierarchy($category, $parentIds);
        if ($hierarchyError) {
            return redirect()->back()
                ->withInput()
                ->with('parent_error', $hierarchyError);
        }

        // Set the primary parent for the database column (if any)
        if (count($parentIds) > 0) {
            $category->parent_id = $parentIds[0]; // Use the first parent as the main one
        } else {
            $category->parent_id = null;
        }

        // Handle photo upload if present with better error handling
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            try {
                $photo = $request->file('photo');
                $filename = time() . '_' . Str::slug($validated['name']) . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('categories', $filename, 'public');
                $category->photo = $path;
            } catch (\Exception $e) {
                // Log the error but don't fail the save operation
                Log::error('Category image upload failed: ' . $e->getMessage());
            }
        }

        $category->save();

        // Add all parent relationships to the pivot table
        try {
            if (count($parentIds) > 0) {
                Log::info('Syncing parent relationships', ['category_id' => $category->id, 'parentIds' => $parentIds]);
                $category->parents()->sync($parentIds);
            }
        } catch (\Exception $e) {
            Log::error('Error syncing parent relationships', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Update an existing category
     */
    public function update(Request $request, Category $category)
    {
        // Debug logging
        Log::info('Category update method called', ['request' => $request->all(), 'category_id' => $category->id]);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|array',
            'parent_id.*' => 'exists:categories,id',
            'type' => 'required|in:main,clothing,item_type',
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            'photo' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ]);

        // Debug parent_id values
        Log::info('Parent IDs before processing', ['parent_id' => $request->parent_id]);

        $category->name = $validated['name'];
        $category->type = $validated['type'];
        $category->slug = $validated['slug'] ?? Str::slug($validated['name']);
        $category->description = $validated['description'] ?? null;
        $category->status = $request->has('status') ? true : false;

        // Process parent_id array - ensure it's an array even if a single value is submitted
        $parentIds = [];
        if ($request->has('parent_id')) {
            if (is_array($request->parent_id)) {
                $parentIds = $request->parent_id;
            } else {
                $parentIds = [$request->parent_id];
            }
            
            // Filter out any empty values
            $parentIds = array_filter($parentIds, function($id) {
                return !empty($id);
            });
            
            Log::info('Parent IDs after filtering', ['parentIds' => $parentIds]);
        }

        // Validate hierarchy based on type
        $hierarchyError = $this->validateHierarchy($category, $parentIds);
        if ($hierarchyError) {
            return redirect()->back()
                ->withInput()
                ->with('parent_error', $hierarchyError);
        }

        // Set the primary parent for the database column (if any)
        if (count($parentIds) > 0) {
            $category->parent_id = $parentIds[0]; // Use the first parent as the main one
        } else {
            $category->parent_id = null;
        }

        // Handle photo upload if present
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old photo if exists
            if ($category->photo) {
                Storage::disk('public')->delete($category->photo);
            }

            $photo = $request->file('photo');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('categories', $filename, 'public');
            $category->photo = $path;
        }

        $category->save();

        // Update all parent relationships in the pivot table
        try {
            if (count($parentIds) > 0) {
                Log::info('Syncing parent relationships', ['category_id' => $category->id, 'parentIds' => $parentIds]);
                $category->parents()->sync($parentIds);
            } else {
                Log::info('Detaching all parent relationships', ['category_id' => $category->id]);
                $category->parents()->detach();
            }
        } catch (\Exception $e) {
            Log::error('Error syncing parent relationships', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Delete a category
     */
    public function destroy(Category $category)
    {
        // Check if category has child categories
        if ($category->hasChildren()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with child categories. Please delete child categories first.');
        }
        
        // Delete category photo if exists
        if ($category->photo) {
            Storage::disk('public')->delete($category->photo);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
    
    /**
     * Get available category types
     */
    private function getCategoryTypes(): array
    {
        return [
            'main' => 'Main Category (Target Group)',
            'clothing' => 'Clothing Type',
            'item_type' => 'Specific Item Type'
        ];
    }
    
    /**
     * Get filtered categories by parent
     */
    public function getChildCategories(Request $request)
    {
        $parentId = $request->input('parent_id');
        
        $categories = Category::where('parent_id', $parentId)
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);
            
        return response()->json($categories);
    }
    
    /**
     * Validate hierarchy based on category type
     * @param Category $category The category to validate
     * @param array|null $parentIds The parent IDs to validate against
     * @return string|null Error message if validation fails, null if valid
     */
    private function validateHierarchy(Category $category, $parentIds = null)
    {
        // Debug the input
        Log::info('validateHierarchy called', ['category_type' => $category->type, 'parentIds' => $parentIds]);
        
        // If the passed parent_id is an array, check if it's empty or not
        if (is_array($parentIds)) {
            if (empty($parentIds) || (count($parentIds) === 1 && empty($parentIds[0]))) {
                $parentIds = null; // Treat empty array as null (no parents)
            }
        }
        
        switch ($category->type) {
            case 'main':
                // Main categories should not have parents
                if ($parentIds) {
                    return 'Main categories cannot have parent categories.';
                }
                $category->parent_id = null;
                break;
                
            case 'clothing':
                // Clothing types must have at least one main category as parent
                if (!$parentIds) {
                    return 'Clothing type categories must have at least one parent Main category.';
                }
                
                // Check that at least one parent is a main category
                $validParents = Category::whereIn('id', $parentIds)
                    ->where('type', 'main')
                    ->count();
                
                if ($validParents === 0) {
                    return 'Clothing type categories must have at least one Main category as parent.';
                }
                
                if ($validParents !== count($parentIds)) {
                    Log::warning('Some selected parents are not main categories', [
                        'valid_main_parents' => $validParents, 
                        'total_parents' => count($parentIds)
                    ]);
                    
                    // For multi-parent support, we allow mixed parent types but ensure at least one is valid
                    // You could change this to return an error if needed
                }
                break;
                
            case 'item_type':
                // Item types must have at least one clothing type as parent
                if (!$parentIds) {
                    return 'Item type categories must have at least one parent Clothing type category.';
                }
                
                // Check that at least one parent is a clothing category
                $validParents = Category::whereIn('id', $parentIds)
                    ->where('type', 'clothing')
                    ->count();
                
                if ($validParents === 0) {
                    return 'Item type categories must have at least one Clothing type category as parent.';
                }
                
                if ($validParents !== count($parentIds)) {
                    Log::warning('Some selected parents are not clothing categories', [
                        'valid_clothing_parents' => $validParents, 
                        'total_parents' => count($parentIds)
                    ]);
                    
                    // For multi-parent support, we allow mixed parent types but ensure at least one is valid
                }
                break;
        }
        
        return null; // No errors
    }

    /**
     * Test method to verify controller is working
     */
    public function testController()
    {
        return response()->json(['success' => true, 'message' => 'CategoryController is working']);
    }
} 