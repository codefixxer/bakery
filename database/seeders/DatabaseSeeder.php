<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\EquipmentSeeder;
use Database\Seeders\LaborCostSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\IngredientSeeder;
use Database\Seeders\PastryChefSeeder;
use Database\Seeders\CostCategorySeeder;
use Database\Seeders\RecipeCategorySeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
 
    public function run()
    {
        $this->call([
                         
            PermissionsTableSeeder::class,        
            RolesAndPermissionsSeeder::class,       
            UserSeeder::class, 

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
