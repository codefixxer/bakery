<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Recipe;
use App\Models\Ingredient;

class RecipeIngredientSeeder extends Seeder
{
    public function run(): void
    {
        $cakeId = DB::table('recipes')->where('recipe_name', 'Chocolate Cake')->value('id');
        $pizzaId = DB::table('recipes')->where('recipe_name', 'Pizza Dough')->value('id');

        $milk = DB::table('ingredients')->where('ingredient_name', 'Milk')->value('id');
        $sugar = DB::table('ingredients')->where('ingredient_name', 'Sugar')->value('id');
        $flour = DB::table('ingredients')->where('ingredient_name', 'Flour')->value('id');
        $cheese = DB::table('ingredients')->where('ingredient_name', 'Mozzarella Cheese')->value('id');

        DB::table('recipe_ingredient')->insert([
            [
                'recipe_id' => $cakeId,
                'ingredient_id' => $milk,
                'quantity_g' => 200,
                'cost' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $cakeId,
                'ingredient_id' => $sugar,
                'quantity_g' => 100,
                'cost' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $pizzaId,
                'ingredient_id' => $flour,
                'quantity_g' => 300,
                'cost' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $pizzaId,
                'ingredient_id' => $cheese,
                'quantity_g' => 100,
                'cost' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
