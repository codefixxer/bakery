<?php

namespace App\Http\Controllers;

use App\Models\User;
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
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->ingredients()->delete(); // if cascade not set
        $recipe->delete();

        return redirect()->route('recipes.index')
                         ->with('success', 'Recipe deleted successfully.');
    }


    public function edit(Recipe $recipe)
    {
        $recipe->load('ingredients');
        $laborCost   = LaborCost::first();
        $ingredients = Ingredient::orderBy('ingredient_name')->get();
        $categories  = RecipeCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('frontend.recipe.create', compact(
            'recipe', 'laborCost', 'ingredients', 'categories', 'departments'
        ));
    }

    public function calculateCost(Request $request)
    {
        $ingredient = Ingredient::find($request->ingredient_id);

        if ($ingredient) {
            $pricePerGram = $ingredient->price_per_kg / 1000;
            $totalCost    = $pricePerGram * $request->quantity;
            return response()->json(['cost' => number_format($totalCost, 2)]);
        }

        return response()->json(['cost' => '0.00']);
    }







    public function create()
    {
        $user = Auth::user();
    
        // Determine group root admin
        $groupRootId = $user->created_by ?? $user->id;
    
        // Get all users in this group (admin + their users)
        $groupUserIds = User::where('created_by', $groupRootId)
                            ->pluck('id')
                            ->push($groupRootId);
    
                            $laborCost = \App\Models\LaborCost::where('user_id', $groupRootId)->first();
    
        $ingredients = Ingredient::whereIn('user_id', $groupUserIds)
                                 ->orderBy('ingredient_name')
                                 ->get();
    
        $categories  = RecipeCategory::whereIn('user_id', $groupUserIds)
                                     ->orderBy('name')
                                     ->get();
    
        $departments = Department::whereIn('user_id', $groupUserIds)
                                 ->orderBy('name')
                                 ->get();
    
        return view('frontend.recipe.create', compact(
            'laborCost', 'ingredients', 'categories', 'departments'
        ));
    }
    
    public function index()
{
    $user = Auth::user();

    // 1) Build your two‑level group of user IDs
    if (is_null($user->created_by)) {
        // Top‑level user: yourself + anyone you created
        $visibleUserIds = User::where('created_by', $user->id)
                              ->pluck('id')
                              ->push($user->id)
                              ->unique();
    } else {
        // Child user: yourself + your creator
        $visibleUserIds = collect([$user->id, $user->created_by])->unique();
    }

    // 2) Fetch only recipes created by those IDs
    $recipes = Recipe::with(['category:id,name','department:id,name','ingredients.ingredient'])
                     ->whereIn('user_id', $visibleUserIds)
                     ->get();

    $departments = Department::whereIn('user_id', $visibleUserIds)
                             ->orderBy('name')
                             ->get();

    $categories  = RecipeCategory::whereIn('user_id', $visibleUserIds)
                                 ->orderBy('name')
                                 ->get();

    return view('frontend.recipe.index', compact('recipes','departments','categories'));
}

    

    public function show(Recipe $recipe)
    {
        // Eager‐load relationships if you need them:
        // $recipe->load('category', 'department', 'ingredients.ingredient');

        return view('frontend.recipe.show', compact('recipe'));
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
            'total_pieces'             => 'nullable|numeric|min:0',
            'recipe_weight'            => 'nullable|numeric|min:0',
            'add_as_ingredient'        => 'sometimes|boolean',
            'labor_cost_mode'          => 'required|in:shop,external',
        ]);

        DB::beginTransaction();
        try {
            // create recipe, stamping with current user
            $recipe = Recipe::create([
                'user_id'            => Auth::id(),
                'recipe_name'            => $data['recipe_name'],
                'recipe_category_id'     => $data['recipe_category_id'],
                'department_id'          => $data['department_id'],
                'sell_mode'              => $data['sell_mode'],
                'selling_price_per_piece'=> $data['selling_price_per_piece']  ?? 0,
                'selling_price_per_kg'   => $data['selling_price_per_kg']    ?? 0,
                'labour_time_min'        => $data['labor_time_input'],
                'labour_cost'            => $data['labor_cost'],
                'packing_cost'           => $data['packing_cost']           ?? 0,
                'production_cost_per_kg' => $data['production_cost_per_kg'],
                'ingredients_total_cost' => $data['ingredients_total_cost'],
                'total_expense'          => $data['total_expense'],
                'potential_margin'       => $data['potential_margin'],
                'total_pieces'           => $data['total_pieces']           ?? 0,
                'recipe_weight'          => $data['recipe_weight']          ?? 0,
                'labor_cost_mode'        => $data['labor_cost_mode'],
                'user_id'                => Auth::id(),
            ]);

            // attach ingredients
            foreach ($data['ingredients'] as $line) {
                $recipe->ingredients()->create([
                    'ingredient_id' => $line['id'],
                    'quantity_g'    => $line['quantity'],
                    'cost'          => $line['cost'],
                ]);
            }

            // optionally add as ingredient
            if ($request->boolean('add_as_ingredient')) {
                Ingredient::create([
                    'ingredient_name' => $data['recipe_name'],
                    'price_per_kg'    => $data['production_cost_per_kg'],
                    'user_id'         => Auth::id(),
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
            'recipe_name'            => 'required|string|max:255',
            'recipe_category_id'     => 'required|exists:recipe_categories,id',
            'department_id'          => 'required|exists:departments,id',
            'sell_mode'              => 'required|in:piece,kg',
            'selling_price_per_piece'=> 'nullable|numeric|min:0',
            'selling_price_per_kg'   => 'nullable|numeric|min:0',
            'labor_time_input'       => 'required|integer|min:0',
            'labor_cost'             => 'required|numeric|min:0',
            'packing_cost'           => 'nullable|numeric|min:0',
            'production_cost_per_kg' => 'required|numeric|min:0',
            'ingredients_total_cost' => 'required|numeric|min:0',
            'total_expense'          => 'required|numeric|min:0',
            'potential_margin'       => 'required|numeric',
            'ingredients'            => 'required|array|min:1',
            'ingredients.*.id'       => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.cost'     => 'required|numeric|min:0',
            'total_pieces'           => 'nullable|numeric|min:0',
            'recipe_weight'          => 'nullable|numeric|min:0',
            'add_as_ingredient'      => 'sometimes|boolean',
            'labor_cost_mode'        => 'required|in:shop,external',
        ]);

        DB::beginTransaction();
        try {
            $recipe->update([
                'recipe_name'            => $data['recipe_name'],
                'recipe_category_id'     => $data['recipe_category_id'],
                'department_id'          => $data['department_id'],
                'sell_mode'              => $data['sell_mode'],
                'selling_price_per_piece'=> $data['selling_price_per_piece']  ?? 0,
                'selling_price_per_kg'   => $data['selling_price_per_kg']    ?? 0,
                'labour_time_min'        => $data['labor_time_input'],
                'labour_cost'            => $data['labor_cost'],
                'packing_cost'           => $data['packing_cost']           ?? 0,
                'production_cost_per_kg' => $data['production_cost_per_kg'],
                'ingredients_total_cost' => $data['ingredients_total_cost'],
                'total_expense'          => $data['total_expense'],
                'potential_margin'       => $data['potential_margin'],
                'total_pieces'           => $data['total_pieces']           ?? 0,
                'recipe_weight'          => $data['recipe_weight']          ?? 0,
                'labor_cost_mode'        => $data['labor_cost_mode'],
            ]);


            

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
                    [
                      'price_per_kg'    => $data['production_cost_per_kg'],
                      'user_id'         => Auth::id(),
                    ]
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
