<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
 
    public function run()
    {
        $this->call([
            IngredientSeeder::class,  
            LaborCostSeeder::class, 
            ClientSeeder::class,     
            CostCategorySeeder::class,     
            PastryChefSeeder::class,     
            EquipmentSeeder::class,     
            DepartmentSeeder::class,     


            RecipeCategorySeeder::class,
            RecipeSeeder::class,
            RecipeIngredientSeeder::class,



            ExternalSupplySeeder::class,
            ExternalSupplyRecipeSeeder::class,

            ShowcaseSeeder::class,
            ShowcaseRecipeSeeder::class,


        ]);        
    }
}
