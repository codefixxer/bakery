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
    
        // 1) Build the “group” of IDs you should see
        if (is_null($user->created_by)) {
            // You’re a root user: see yourself + anyone you created
            $visibleUserIds = \App\Models\User::where('created_by', $user->id)
                                   ->pluck('id')
                                   ->push($user->id)
                                   ->unique();
        } else {
            // You’re a child: see yourself + your creator
            $visibleUserIds = collect([$user->id, $user->created_by])->unique();
        }
    
        // 2) Fetch categories in your group OR with status = 'Default'
        $categories = \App\Models\RecipeCategory::with('user')
            ->where(function($q) use ($visibleUserIds) {
                $q->whereIn('user_id', $visibleUserIds)
                  ->orWhere('status', 'Default');
            })
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