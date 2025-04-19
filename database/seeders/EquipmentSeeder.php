<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('equipment')->insert([
            [
                'name' => 'Oven - Large Capacity',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mixer - Dough Pro 5000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Refrigerator - Industrial',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Convection Oven - Medium',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pastry Sheeter',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
