<?php

namespace App\Http\Controllers;

use App\Models\CostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CostCategoryController extends Controller
{
    /**
     * Show form to create a category.
     */
    public function create()
    {
        return view('frontend.categories.create');
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CostCategory::create($data);

        return redirect()->back()->with('success', 'CostCategory added successfully!');
    }

    /**
     * Show form to edit a category.
     */
    public function edit(CostCategory $category)
    {
        return view('frontend.categories.create', compact('category'));
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, CostCategory $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        return redirect()->route('categories.create')->with('success', 'CostCategory updated successfully!');
    }




    public function index()
    {
        $categories = CostCategory::latest()->get();
        return view('frontend.categories.index', compact('categories'));
    }

    
    


    /**
     * Optional: Delete a category.
     */
    public function destroy(CostCategory $category)
    {
        $category->delete();
        return back()->with('success', 'CostCategory deleted.');
    }
}
