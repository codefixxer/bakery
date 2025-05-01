<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\LaborCost;
use App\Models\Department;
use App\Models\Ingredient;
use App\Models\CostCategory;
use Illuminate\Http\Request;
use App\Models\RecipeCategory;
use App\Models\IngredientRecipe;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{


    // app/Http/Controllers/RecipeController.php

    public function index()
    {
        // eager-load category, department & ingredients
        $recipes     = Recipe::with(['category:id,name', 'department:id,name', 'ingredients.ingredient'])->get();
        $departments = Department::orderBy('name')->get();
        $categories  = RecipeCategory::orderBy('name')->get(); // Eager-load categories for the filter

        // Pass categories to the view
        return view('frontend.recipe.index', compact('recipes', 'departments', 'categories'));
    }

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);

        // Delete all related ingredients (optional if using cascade)
        $recipe->ingredients()->delete(); // only if needed

        // Delete the recipe
        $recipe->delete();

        return redirect()->route('recipes.index')
            ->with('success', 'Recipe deleted successfully.');
    }





    public function create()
    {
        $laborCost   = LaborCost::first();
        $ingredients = Ingredient::orderBy('ingredient_name')->get();
        $categories  = RecipeCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();          // ← new
        return view(
            'frontend.recipe.create',
            compact('ingredients', 'laborCost', 'categories', 'departments')
        );
    }

    public function edit(Recipe $recipe)
    {
        $recipe->load('ingredients');
        $laborCost   = LaborCost::first();
        $ingredients = Ingredient::orderBy('ingredient_name')->get();
        $categories  = RecipeCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();          // ← new
        return view(
            'frontend.recipe.create',
            compact('recipe', 'ingredients', 'laborCost', 'categories', 'departments')
        );
    }








    public function calculateCost(Request $request)
    {
        $ingredient = Ingredient::find($request->ingredient_id);

        if ($ingredient) {
            // Calculate the cost per gram
            $pricePerKg = $ingredient->price_per_kg;
            $quantityInGrams = $request->quantity;
            $pricePerGram = $pricePerKg / 1000;

            // Calculate the total cost
            $totalCost = $pricePerGram * $quantityInGrams;

            // Return the calculated cost as JSON response
            return response()->json(['cost' => number_format($totalCost, 2)]);
        }

        return response()->json(['cost' => 0.00]); // Return 0 if ingredient not found
    }








    public function store(Request $request)
    {
        $data = $request->validate([
            'recipe_name'              => 'required|string|max:255',
            'recipe_category_id'       => 'required|exists:recipe_categories,id',
            'department_id'            => 'required|exists:departments,id',
            'sell_mode'                => 'required|in:piece,kg',
            'selling_price_per_piece'  => 'nullable|numeric|min:0',
            'selling_price_per_kg'     => 'nullable|numeric|min:0',
            'labor_time_input'         => 'required|integer|min:0',
            'labor_cost'               => 'required|numeric|min:0',
            'packing_cost'             => 'nullable|numeric|min:0',
            'production_cost_per_kg'   => 'required|numeric|min:0',
            'ingredients_total_cost'   => 'required|numeric|min:0',
            'total_expense'            => 'required|numeric|min:0',
            'potential_margin'         => 'required|numeric',
            'ingredients'              => 'required|array|min:1',
            'ingredients.*.id'         => 'required|exists:ingredients,id',
            'ingredients.*.quantity'   => 'required|numeric|min:0',
            'ingredients.*.cost'       => 'required|numeric|min:0',
            'total_pieces'             => 'nullable|integer|min:0',
            'recipe_weight'            => 'nullable|numeric|min:0',
            'add_as_ingredient'        => 'sometimes|boolean',
            'labor_cost_mode'          => 'required|in:shop,external',
            'production_cost_per_kg'          => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $recipe = Recipe::create([
                'recipe_name'            => $data['recipe_name'],
                'recipe_category_id'     => $data['recipe_category_id'],
                'department_id'          => $data['department_id'],
                'sell_mode'              => $data['sell_mode'],
                'selling_price_per_piece' => $data['selling_price_per_piece']  ?? 0,
                'selling_price_per_kg'   => $data['selling_price_per_kg']    ?? 0,
                'labour_time_min'        => $data['labor_time_input'],
                'labour_cost'            => $data['labor_cost'],
                'packing_cost'           => $data['packing_cost'],
                'production_cost_per_kg' => $data['production_cost_per_kg'],
                'ingredients_total_cost' => $data['ingredients_total_cost'],
                'total_expense'          => $data['total_expense'],
                'potential_margin'       => $data['potential_margin'],
                'total_pieces'           => $data['total_pieces']           ?? 0,
                'recipe_weight'          => $data['recipe_weight']          ?? 0,
                'labor_cost_mode'        => $data['labor_cost_mode'],
                'production_cost_per_kg'        => $data['production_cost_per_kg'],
            ]);

            foreach ($data['ingredients'] as $line) {
                $recipe->ingredients()->create([
                    'ingredient_id' => $line['id'],
                    'quantity_g'    => $line['quantity'],
                    'cost'          => $line['cost'],
                ]);
            }

            if ($request->boolean('add_as_ingredient')) {
                Ingredient::create([
                    'ingredient_name' => $data['recipe_name'],
                    'price_per_kg'    => $data['production_cost_per_kg'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('recipes.create')
                ->with('success', 'Recipe saved successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save recipe: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Recipe $recipe)
    {
        $data = $request->validate([
            'recipe_name'              => 'required|string|max:255',
            'recipe_category_id'       => 'required|exists:recipe_categories,id',
            'department_id'            => 'required|exists:departments,id',
            'sell_mode'                => 'required|in:piece,kg',
            'selling_price_per_piece'  => 'nullable|numeric|min:0',
            'selling_price_per_kg'     => 'nullable|numeric|min:0',
            'labor_time_input'         => 'required|integer|min:0',
            'labor_cost'               => 'required|numeric|min:0',
            'packing_cost'             => 'nullable|numeric|min:0',
            'production_cost_per_kg'   => 'required|numeric|min:0',
            'ingredients_total_cost'   => 'required|numeric|min:0',
            'total_expense'            => 'required|numeric|min:0',
            'potential_margin'         => 'required|numeric',
            'ingredients'              => 'required|array|min:1',
            'ingredients.*.id'         => 'required|exists:ingredients,id',
            'ingredients.*.quantity'   => 'required|numeric|min:0',
            'ingredients.*.cost'       => 'required|numeric|min:0',
            'total_pieces'             => 'nullable|integer|min:0',
            'recipe_weight'            => 'nullable|numeric|min:0',
            'add_as_ingredient'        => 'sometimes|boolean',
            'labor_cost_mode'          => 'required|in:shop,external',
            'production_cost_per_kg'          => 'required',
        ]);

        DB::beginTransaction();
        try {
            $recipe->update([
                'recipe_name'            => $data['recipe_name'],
                'recipe_category_id'     => $data['recipe_category_id'],
                'department_id'          => $data['department_id'],
                'sell_mode'              => $data['sell_mode'],
                'selling_price_per_piece' => $data['selling_price_per_piece']  ?? 0,
                'selling_price_per_kg'   => $data['selling_price_per_kg']    ?? 0,
                'labour_time_min'        => $data['labor_time_input'],
                'labour_cost'            => $data['labor_cost'],
                'packing_cost'           => $data['packing_cost'],
                'production_cost_per_kg' => $data['production_cost_per_kg'],
                'ingredients_total_cost' => $data['ingredients_total_cost'],
                'total_expense'          => $data['total_expense'],
                'potential_margin'       => $data['potential_margin'],
                'total_pieces'           => $data['total_pieces']           ?? 0,
                'recipe_weight'          => $data['recipe_weight']          ?? 0,
                'labor_cost_mode'        => $data['labor_cost_mode'],
                'production_cost_per_kg'        => $data['production_cost_per_kg'],
            ]);

            // remove old lines and re-insert
            $recipe->ingredients()->delete();
            foreach ($data['ingredients'] as $line) {
                $recipe->ingredients()->create([
                    'ingredient_id' => $line['id'],
                    'quantity_g'    => $line['quantity'],
                    'cost'          => $line['cost'],
                ]);
            }

            if ($request->boolean('add_as_ingredient')) {
                Ingredient::updateOrCreate(
                    ['ingredient_name' => $data['recipe_name']],
                    ['price_per_kg'    => $data['production_cost_per_kg']]
                );
            }

            DB::commit();

            return redirect()
                ->route('recipes.index')
                ->with('success', 'Recipe updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update recipe: ' . $e->getMessage()]);
        }
    }
}
