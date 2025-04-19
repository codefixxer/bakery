<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        DB::table('ingredients')->insert([
            ['ingredient_name' => 'Milk', 'price_per_kg' => 50.00, 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_name' => 'Cream', 'price_per_kg' => 100.00, 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_name' => 'Sugar', 'price_per_kg' => 100.00, 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_name' => 'Flour', 'price_per_kg' => 30.00, 'created_at' => now(), 'updated_at' => now()],
            ['ingredient_name' => 'Mozzarella Cheese', 'price_per_kg' => 150.00, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
