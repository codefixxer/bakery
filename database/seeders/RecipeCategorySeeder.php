<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecipeCategory;

class RecipeCategorySeeder extends Seeder
{
    public function run(): void
    {
        /* seed a small default list; extend as you like */
        $names = ['Dessert', 'Base', 'Beverage', 'Sauce'];

        foreach ($names as $name) {
            RecipeCategory::firstOrCreate(['name' => $name]);
        }
    }
}
