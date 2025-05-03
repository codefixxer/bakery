<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ingredient;

class IngredientController extends Controller
{


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

        // stamp with the current user's ID
        $data['user_id'] = Auth::id();

        $ingredient = Ingredient::updateOrCreate(
            ['ingredient_name' => $data['ingredient_name']],
            $data
        );

        if ($request->expectsJson()) {
            return response()->json($ingredient, 201);
        }

        return back()->with('success', 'Ingredient saved successfully.');
    }
    public function index()
    {
        $ingredients = Ingredient::where('user_id',Auth::id())->get();
        return view('frontend.ingredients.index', compact('ingredients'));
    }





    
    public function edit(Ingredient $ingredient)
    {
        return view('frontend.ingredients.create', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'price_per_kg'    => 'required|numeric|min:0',
        ]);

        $ingredient->update($data);

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Ingredient updated successfully!');
    }


    public function show(Ingredient $ingredient)
{
    return view('frontend.ingredients.show', compact('ingredient'));
}


    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Ingredient deleted successfully!');
    }
}
