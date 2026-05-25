<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = [
            ['name' => 'Blaugrana Hall',  'code' => 'BH', 'total_floors' => 4, 'gender_type' => 'male',   'is_active' => true],
            ['name' => 'Nou Camp Wing',   'code' => 'NC', 'total_floors' => 3, 'gender_type' => 'female', 'is_active' => true],
            ['name' => 'Masia Residence', 'code' => 'MR', 'total_floors' => 5, 'gender_type' => 'mixed',  'is_active' => true],
        ];

        foreach ($buildings as $building) {
            Building::firstOrCreate(['code' => $building['code']], $building);
        }
    }
}