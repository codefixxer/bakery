<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\RecipeCategory;
use App\Models\Department;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // look‑up the category IDs
        $dessertId = RecipeCategory::where('name', 'Dessert')->value('id');
        $baseId    = RecipeCategory::where('name', 'Base')->value('id');

        // look‑up the department IDs
        $prodDeptId = Department::where('name', 'Production')->value('id');
        $packDeptId = Department::where('name', 'Packaging')->value('id');

        DB::table('recipes')->insert([
            [
                'recipe_name'            => 'Chocolate Cake',
                'recipe_category_id'     => $dessertId,
                'department_id'          => $prodDeptId,          // ← added
                'sell_mode'              => 'piece',
                'selling_price_per_piece'=> 150.00,
                'selling_price_per_kg'   => null,
                'labour_time_min'        => 30,
                'labour_cost'            => 50,
                'packing_cost'           => 10,
                'ingredients_total_cost' => 100,
                'total_expense'          => 160,
                'potential_margin'       => -10,
                'created_at'             => $now,
                'updated_at'             => $now,
            ],
            [
                'recipe_name'            => 'Pizza Dough',
                'recipe_category_id'     => $baseId,
                'department_id'          => $packDeptId,          // ← added
                'sell_mode'              => 'kg',
                'selling_price_per_piece'=> null,
                'selling_price_per_kg'   => 90.00,
                'labour_time_min'        => 20,
                'labour_cost'            => 30,
                'packing_cost'           => 5,
                'ingredients_total_cost' => 50,
                'total_expense'          => 85,
                'potential_margin'       => 5,
                'created_at'             => $now,
                'updated_at'             => $now,
            ],
        ]);
    }
}
