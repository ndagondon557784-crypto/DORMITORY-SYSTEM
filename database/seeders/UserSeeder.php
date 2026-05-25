<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $staffRole = Role::where('name', 'staff')->first();

        User::firstOrCreate(['email' => 'admin@dormitory.edu'], [
            'role_id'  => $adminRole->id,
            'name'     => 'System Administrator',
            'password' => Hash::make('Admin@2024!'),
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'staff@dormitory.edu'], [
            'role_id'  => $staffRole->id,
            'name'     => 'Dorm Staff',
            'password' => Hash::make('Staff@2024!'),
            'is_active' => true,
        ]);
    }
}