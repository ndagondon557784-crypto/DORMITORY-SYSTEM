<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = Building::all();

        foreach ($buildings as $building) {
            for ($floor = 1; $floor <= min($building->total_floors, 2); $floor++) {
                for ($num = 1; $num <= 5; $num++) {
                    $roomNumber = $floor . sprintf('%02d', $num);
                    $type = match ($num % 4) {
                        1 => 'single',
                        2 => 'double',
                        3 => 'triple',
                        0 => 'quad',
                    };
                    $capacity = match ($type) {
                        'single' => 1,
                        'double' => 2,
                        'triple' => 3,
                        'quad'   => 4,
                    };

                    Room::firstOrCreate(
                        ['building_id' => $building->id, 'room_number' => $roomNumber],
                        [
                            'floor'             => $floor,
                            'room_type'         => $type,
                            'capacity'          => $capacity,
                            'current_occupancy' => 0,
                            'monthly_rate'      => rand(2000, 5000),
                            'status'            => 'available',
                        ]
                    );
                }
            }
        }
    }
}