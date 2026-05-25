<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin',   'display_name' => 'Administrator'],
            ['name' => 'staff',   'display_name' => 'Staff'],
            ['name' => 'student', 'display_name' => 'Student'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['display_name' => $role['display_name']]
            );
        }

        $this->command->info('✅ Roles seeded: admin, staff, student');
    }
}