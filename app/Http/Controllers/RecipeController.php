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
        $user = Auth::user();

        $groupRootId   = $user->created_by ?: $user->id;

        $recipe->load('ingredients');
        $laborCost = \App\Models\LaborCost::where('user_id', $groupRootId)->first();
        $ingredients = Ingredient::orderBy('ingredient_name')->get();
        $categories  = RecipeCategory::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('frontend.recipe.create', compact(
            'recipe',
            'laborCost',
            'ingredients',
            'categories',
            'departments'
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
        $laborCost = \App\Models\LaborCost::where('user_id', $groupRootId)->first();

        // Get all users in this group (admin + their users)
        $groupUserIds = User::where('created_by', $groupRootId)
            ->pluck('id')
            ->push($groupRootId);


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
            'laborCost',
            'ingredients',
            'categories',
            'departments'
        ));
    }

    // app/Http/Controllers/RecipeController.php

    public function index()
    {
        $user = Auth::user();

        // 1) Find “group root” and load its single LaborCost record
        $groupRootId   = $user->created_by ?: $user->id;
        $laborCostRate = LaborCost::where('user_id', $groupRootId)->first();

        // 2) Build visible-user list
        if (is_null($user->created_by)) {
            $visibleUserIds = User::where('created_by', $user->id)
                ->pluck('id')
                ->push($user->id)
                ->unique();
        } else {
            $visibleUserIds = collect([$user->id, $user->created_by])->unique();
        }

        // 3) Fetch recipes + relations
        $recipes = Recipe::with([
            'category:id,name',
            'department:id,name',
            'ingredients.ingredient',
        ])
            ->whereIn('user_id', $visibleUserIds)
            ->get();

        // 4) Compute a batch_labor_cost & batch_ing_cost property on each Recipe
        $recipes->each(function ($r) use ($laborCostRate) {
            // labor
            $rate = $r->labor_cost_mode === 'external'
                ? ($laborCostRate->external_cost_per_min ?? 0)
                : ($laborCostRate->shop_cost_per_min     ?? 0);
            $r->batch_labor_cost = round($r->labour_time_min * $rate, 2);

            // ingredients
            $r->batch_ing_cost = $r->ingredients_cost_per_batch;
        });

        // 5) Load departments & categories for filters
        $departments = Department::whereIn('user_id', $visibleUserIds)
            ->orderBy('name')
            ->get();

        $categories  = RecipeCategory::whereIn('user_id', $visibleUserIds)
            ->orderBy('name')
            ->get();

        return view('frontend.recipe.index', compact(
            'recipes',
            'departments',
            'categories'
        ));
    }




    public function show(Recipe $recipe)
    {
        // eager-load everything we need:
        $recipe->load([
            'category',
            'department',
            'ingredients.ingredient',
            'laborCostRate'
        ]);

        return view('frontend.recipe.show', compact('recipe'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'recipe_name'             => 'required|string|max:255',
            'recipe_category_id'      => 'required|exists:recipe_categories,id',
            'department_id'           => 'required|exists:departments,id',
            'sell_mode'               => 'required|in:piece,kg',
            'selling_price_per_piece' => 'nullable|numeric|min:0',
            'selling_price_per_kg'    => 'nullable|numeric|min:0',
            'labor_cost_id'           => 'required|exists:labor_costs,id',
            'labor_time_input'        => 'required|integer|min:0',
            'labor_cost_mode'         => 'required|in:shop,external',
            'packing_cost'            => 'nullable|numeric|min:0',
            'production_cost_per_kg'  => 'required|numeric|min:0',
            'total_expense'           => 'required|numeric|min:0',
            'potential_margin'        => 'required|numeric',
            'ingredients'             => 'required|array|min:1',
            'ingredients.*.id'        => 'required|exists:ingredients,id',
            'ingredients.*.quantity'  => 'required|numeric|min:0',
            'total_pieces'            => 'nullable|numeric|min:0',
            'recipe_weight'           => 'nullable|numeric|min:0',
            'add_as_ingredient'       => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($data, $request) {
            $recipe = Recipe::create([
                'user_id'                  => Auth::id(),
                'recipe_name'              => $data['recipe_name'],
                'recipe_category_id'       => $data['recipe_category_id'],
                'department_id'            => $data['department_id'],
                'sell_mode'                => $data['sell_mode'],
                'selling_price_per_piece'  => $data['selling_price_per_piece'] ?? 0,
                'selling_price_per_kg'     => $data['selling_price_per_kg']  ?? 0,
                'labor_cost_id'            => $data['labor_cost_id'],
                'labour_time_min'          => $data['labor_time_input'],
                'labor_cost_mode'          => $data['labor_cost_mode'],
                'packing_cost'             => $data['packing_cost']           ?? 0,
                'production_cost_per_kg'   => $data['production_cost_per_kg'],
                'total_expense'            => $data['total_expense'],
                'potential_margin'         => $data['potential_margin'],
                'total_pieces'             => $data['total_pieces']           ?? 0,
                'recipe_weight'            => $data['recipe_weight']          ?? 0,
                'add_as_ingredient'        => $request->boolean('add_as_ingredient'),
            ]);

            foreach ($data['ingredients'] as $line) {
                $recipe->ingredients()->create([
                    'ingredient_id' => $line['id'],
                    'quantity_g'    => $line['quantity'],
                ]);
            }

            if ($request->boolean('add_as_ingredient')) {
                Ingredient::create([
                    'ingredient_name' => $data['recipe_name'],
                    'price_per_kg'    => $data['production_cost_per_kg'],
                    'user_id'         => Auth::id(),
                ]);
            }
        });

        return redirect()->route('recipes.create')
            ->with('success', 'Recipe saved successfully!');
    }

    public function update(Request $request, Recipe $recipe)
    {
        // 1) Validate exactly the same way as store
        $data = $request->validate([
            'recipe_name'             => 'required|string|max:255',
            'recipe_category_id'      => 'required|exists:recipe_categories,id',
            'department_id'           => 'required|exists:departments,id',
            'sell_mode'               => 'required|in:piece,kg',
            'selling_price_per_piece' => 'nullable|numeric|min:0',
            'selling_price_per_kg'    => 'nullable|numeric|min:0',
            'labor_cost_id'           => 'required|exists:labor_costs,id',
            'labor_time_input'        => 'required|integer|min:0',
            'labor_cost_mode'         => 'required|in:shop,external',
            'packing_cost'            => 'nullable|numeric|min:0',
            'production_cost_per_kg'  => 'required|numeric|min:0',
            'total_expense'           => 'required|numeric|min:0',
            'potential_margin'        => 'required|numeric',
            'ingredients'             => 'required|array|min:1',
            'ingredients.*.id'        => 'required|exists:ingredients,id',
            'ingredients.*.quantity'  => 'required|numeric|min:0',
            'total_pieces'            => 'nullable|numeric|min:0',
            'recipe_weight'           => 'nullable|numeric|min:0',
            'add_as_ingredient'       => 'sometimes|boolean',
        ]);

        // 2) Wrap in transaction
        DB::transaction(function () use ($data, $request, $recipe) {
            // 2a) Update the recipe fields
            $recipe->update([
                'recipe_name'              => $data['recipe_name'],
                'recipe_category_id'       => $data['recipe_category_id'],
                'department_id'            => $data['department_id'],
                'sell_mode'                => $data['sell_mode'],
                'selling_price_per_piece'  => $data['selling_price_per_piece'] ?? 0,
                'selling_price_per_kg'     => $data['selling_price_per_kg']  ?? 0,
                'labor_cost_id'            => $data['labor_cost_id'],
                'labour_time_min'          => $data['labor_time_input'],
                'labor_cost_mode'          => $data['labor_cost_mode'],
                'packing_cost'             => $data['packing_cost']           ?? 0,
                'production_cost_per_kg'   => $data['production_cost_per_kg'],
                'total_expense'            => $data['total_expense'],
                'potential_margin'         => $data['potential_margin'],
                'total_pieces'             => $data['total_pieces']           ?? 0,
                'recipe_weight'            => $data['recipe_weight']          ?? 0,
                'add_as_ingredient'        => $request->boolean('add_as_ingredient'),
            ]);

            // 2b) Replace all ingredient lines
            $recipe->ingredients()->delete();
            foreach ($data['ingredients'] as $line) {
                $recipe->ingredients()->create([
                    'ingredient_id' => $line['id'],
                    'quantity_g'    => $line['quantity'],
                ]);
            }

            // 2c) Sync the optional “add as ingredient” behavior
            if ($request->boolean('add_as_ingredient')) {
                Ingredient::updateOrCreate(
                    ['ingredient_name' => $data['recipe_name']],
                    [
                        'price_per_kg' => $data['production_cost_per_kg'],
                        'user_id'      => Auth::id(),
                    ]
                );
            }
        });

        // 3) Redirect back with success
        return redirect()
            ->route('recipes.index')
            ->with('success', 'Recipe updated successfully!');
    }
}
