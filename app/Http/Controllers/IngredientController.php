<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();
        return view('frontend.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('frontend.ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        Ingredient::create($request->all());

        return redirect()->route('ingredients.index')->with('success', 'Ingredient added successfully!');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('frontend.ingredients.create', compact('ingredient'));
    }
    
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
        ]);
    
        $ingredient->update($request->all());
    
        return redirect()->route('ingredients.index')->with('success', 'Ingredient updated successfully!');
    }
    

    public function destroy(Ingredient $ingredient)
{
    $ingredient->delete();

    return redirect()->route('ingredients.index')->with('success', 'Ingredient deleted successfully!');
}




}

