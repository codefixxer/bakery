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
    
        // Super admin → created_by is NULL → show all
        if (is_null($user->created_by)) {
            $ingredients = Ingredient::with('user')->get();
        } else {
            // Determine the group root admin
            $groupRootId = $user->created_by ?? $user->id;
    
            // All users in the same group: admin + their users
            $groupUserIds = User::where('created_by', $groupRootId)
                                ->pluck('id')
                                ->push($groupRootId); // include admin
    
            // Fetch ingredients only for this group
            $ingredients = Ingredient::with('user')
                                     ->whereIn('user_id', $groupUserIds)
                                     ->get();
        }
    
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

        $ingredient->delete();

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Ingredient deleted successfully!');
    }
}
