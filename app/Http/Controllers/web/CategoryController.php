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
        $categories = Category::orderBy('name')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $category = new Category();
        return view('admin.categories.create', compact('category'));
    }

    /**
     * Show the form for editing a category
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Store a new category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->description = $validated['description'] ?? null;

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

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Update an existing category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->description = $validated['description'] ?? null;

        // Handle photo upload if present
        if ($request->hasFile('photo')) {
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

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Delete a category
     */
    public function destroy(Category $category)
    {
        // Delete category photo if exists
        if ($category->photo) {
            Storage::disk('public')->delete($category->photo);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
} 