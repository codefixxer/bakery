<?php

namespace App\Http\Controllers;

use App\Models\CostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CostCategoryController extends Controller
{
    /**
     * Display a listing of the logged‑in user’s categories.
     */
    public function index()
    {
        $categories = CostCategory::where('user_id', Auth::id())
                                  ->latest()
                                  ->get();

        return view('frontend.categories.index', compact('categories'));
    }

    /**
     * Redirect create‑page requests back to the index,
     * since the form lives on the index view.
     */
    public function create()
    {
        return redirect()->route('cost_categories.index');
    }

    public function show(CostCategory $costCategory)
    {
        // Pass the model as $costCategory into the view
        return view('frontend.categories.show', compact('costCategory'));
    }

    /**
     * Store a newly created category for this user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data['user_id'] = Auth::id();

        CostCategory::create($data);

        return redirect()
            ->route('cost_categories.index')
            ->with('success', 'Category added successfully!');
    }

    /**
     * Show the edit form (same index view, but with $category filled).
     */
    public function edit(CostCategory $cost_category)
    {
        if ($cost_category->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $categories = CostCategory::where('user_id', Auth::id())
                                  ->latest()
                                  ->get();

        return view('frontend.categories.create', [
            'category'   => $cost_category,
            'categories' => $categories,
        ]);
    }

    /**
     * Update an existing category (only if it belongs to the user).
     */
    public function update(Request $request, CostCategory $cost_category)
    {
        if ($cost_category->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $cost_category->update($data);

        return redirect()
            ->route('cost_categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Delete a category (only if it belongs to the user).
     */
    public function destroy(CostCategory $cost_category)
    {
        if ($cost_category->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $cost_category->delete();

        return redirect()
            ->route('cost_categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
