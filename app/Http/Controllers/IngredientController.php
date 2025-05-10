<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IngredientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Build the list of user IDs you should see:
        if (is_null($user->created_by)) {
            // Top‑level: see yourself + anyone you created
            $visibleUserIds = User::where('created_by', $user->id)
                                  ->pluck('id')
                                  ->push($user->id)
                                  ->unique();
        } else {
            // Child: see yourself + your creator
            $visibleUserIds = collect([$user->id, $user->created_by])->unique();
        }

        // Fetch ingredients belonging to those users
        $ingredients = Ingredient::with('user')
                          ->whereIn('user_id', $visibleUserIds)
                          ->get();

        return view('frontend.ingredients.index', compact('ingredients'));
    }
    






    
    public function create()
    {
        return view('frontend.ingredients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ingredient_name' => 'required|string|max:255|unique:ingredients,ingredient_name',
            'price_per_kg'    => 'required|numeric|min:0',
        ]);

        $data['user_id'] = Auth::id();

        $ingredient = Ingredient::updateOrCreate(
            ['ingredient_name' => $data['ingredient_name'], 'user_id' => Auth::id()],
            $data
        );

        if ($request->expectsJson()) {
            return response()->json($ingredient, 201);
        }

        return back()->with('success', 'Ingredient saved successfully.');
    }

    public function show(Ingredient $ingredient)
    {
        // Prevent access to others' ingredients
        abort_unless($ingredient->user_id === Auth::id(), 403);

        return view('frontend.ingredients.show', compact('ingredient'));
    }

    public function edit(Ingredient $ingredient)
    {
        // Prevent editing others' ingredients
        abort_unless($ingredient->user_id === Auth::id(), 403);

        return view('frontend.ingredients.create', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        // Prevent updating others' ingredients
        abort_unless($ingredient->user_id === Auth::id(), 403);

        $data = $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'price_per_kg'    => 'required|numeric|min:0',
        ]);

        $ingredient->update($data);

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Ingredient updated successfully!');
    }

    public function destroy(Ingredient $ingredient)
{
    // Prevent deleting others' ingredients
    abort_unless($ingredient->user_id === Auth::id(), 403);

    // 1) detach from any recipes
    $ingredient->recipes()->detach();

    // 2) now it’s safe to delete
    $ingredient->delete();

    return redirect()
        ->route('ingredients.index')
        ->with('success', 'Ingredient deleted successfully!');
}

}
