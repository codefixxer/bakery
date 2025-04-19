<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('cost_categories')->insert([
            [
                'name' => 'Utilities',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rent',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Packaging',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Raw Materials',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Salaries',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
