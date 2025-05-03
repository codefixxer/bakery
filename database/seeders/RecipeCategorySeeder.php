<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecipeCategory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RecipeCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Ensure superadmin exists
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // secure in real apps
            ]
        );

        $userId = $superadmin->id;

        $names = ['Dessert', 'Base', 'Beverage', 'Sauce'];

        foreach ($names as $name) {
            RecipeCategory::firstOrCreate(
                ['name' => $name, 'user_id' => $userId]
            );
        }
    }
}
