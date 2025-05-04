<?php

namespace App\Http\Controllers;

use App\Models\RecipeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class RecipeCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $groupRootId = $user->created_by ?? $user->id;
    
        // Get user IDs of: self, group root (admin), and all super admins
        $groupUserIds = \App\Models\User::where('created_by', $groupRootId)
            ->pluck('id')
            ->push($groupRootId)
            ->merge(
                \App\Models\User::role('super')->pluck('id') // include super admins
            )
            ->unique();
    
        $categories = \App\Models\RecipeCategory::whereIn('user_id', $groupUserIds)
            ->orderBy('name')
            ->get();
    
        return view('frontend.recipe_categories.index', compact('categories'));
    }
    
    
    

    /**
     * Store a new category for this user.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // unique per user
                Rule::unique('recipe_categories')
                    ->where(fn($q) => $q->where('user_id', $userId)),
            ],
        ]);

        $data['user_id'] = $userId;

        RecipeCategory::create($data);

        return back()->with('success', 'Category added!');
    }

    /**
     * Show the edit form (same view, but with $category pre‑filled).
     */
    public function edit(RecipeCategory $recipeCategory)
    {
        $userId = Auth::id();

        // guard: only own records
        if ($recipeCategory->user_id !== $userId) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // only this user’s categories
        $categories = RecipeCategory::where('user_id', $userId)
            ->orderBy('name')
            ->get();

        return view('frontend.recipe_categories.create', [
            'category'   => $recipeCategory,
            'categories' => $categories,
        ]);
    }

    /**
     * Update an existing category, scoped to the user.
     */
    public function update(Request $request, RecipeCategory $recipeCategory)
    {
        $userId = Auth::id();

        // guard: only own records
        if ($recipeCategory->user_id !== $userId) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // unique per user, ignoring this record
                Rule::unique('recipe_categories')
                    ->ignore($recipeCategory->id)
                    ->where(fn($q) => $q->where('user_id', $userId)),
            ],
        ]);

        $recipeCategory->update([
            'name' => $data['name'],
            // we leave user_id unchanged
        ]);

        return redirect()
            ->route('recipe-categories.index')
            ->with('success', 'Category updated!');
    }
    public function show(RecipeCategory $recipeCategory)
    {
        return view('frontend.recipe_categories.show', compact('recipeCategory'));
    }

    /**
     * Delete a category—only if it belongs to the user.
     */
    public function destroy(RecipeCategory $recipeCategory)
    {
        $userId = Auth::id();

        // guard: only own records
        if ($recipeCategory->user_id !== $userId) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $recipeCategory->delete();

        return back()->with('success', 'Category deleted!');
    }
}
