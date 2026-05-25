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
        // Ensure roles exist first
        $adminRole   = Role::where('name', 'admin')->first();
        $staffRole   = Role::where('name', 'staff')->first();
        $studentRole = Role::where('name', 'student')->first();

        if (! $adminRole || ! $staffRole || ! $studentRole) {
            $this->command->error('❌ Roles not found! Run RoleSeeder first.');
            return;
        }

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@dormitory.edu'],
            [
                'role_id'   => $adminRole->id,
                'name'      => 'System Administrator',
                'password'  => Hash::make('Admin@2024!'),
                'is_active' => true,
            ]
        );

        // Staff
        User::updateOrCreate(
            ['email' => 'staff@dormitory.edu'],
            [
                'role_id'   => $staffRole->id,
                'name'      => 'Dorm Staff',
                'password'  => Hash::make('Staff@2024!'),
                'is_active' => true,
            ]
        );

        // Demo student
        User::updateOrCreate(
            ['email' => 'student@dormitory.edu'],
            [
                'role_id'   => $studentRole->id,
                'name'      => 'Demo Student',
                'password'  => Hash::make('Student@2024!'),
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Users seeded: admin, staff, student');
    }
}