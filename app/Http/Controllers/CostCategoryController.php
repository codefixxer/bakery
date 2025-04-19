<?php

namespace App\Http\Controllers;

use App\Models\CostCategory;
use Illuminate\Http\Request;

class CostCategoryController extends Controller
{
    /**
     * Display a listing of categories (and the create form).
     */
    public function index()
    {
        $categories = CostCategory::latest()->get();

        return view('frontend.categories.index', compact('categories'));
    }

    /**
     * Redirect create-page requests back to the index,
     * since the form lives on the index view.
     */
    public function create()
    {
        return redirect()->route('cost_categories.index');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CostCategory::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('cost_categories.index')
            ->with('success', 'Category added successfully!');
    }

    /**
     * Show the edit form (same index view, but with $category filled).
     */
    public function edit(CostCategory $cost_category)
    {
        $categories = CostCategory::latest()->get();

        return view('frontend.categories.create', [
            'category'   => $cost_category,
            'categories' => $categories,
        ]);
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, CostCategory $cost_category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $cost_category->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('cost_categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Delete a category.
     */
    public function destroy(CostCategory $cost_category)
    {
        $cost_category->delete();

        return redirect()
            ->route('cost_categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
