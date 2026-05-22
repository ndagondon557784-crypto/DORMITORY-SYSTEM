<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Room;
use App\Models\Allocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── ADMIN ACCOUNTS ────────────────────────────────────────
        // These are the accounts you use to log in as admin.
        // role = 'admin' → isAdmin() returns true → admin dashboard shown
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@barca.com',      // Login: admin@barca.com / password
            'password' => Hash::make('password'),
            'role'     => 'admin',                 // <-- CRITICAL
        ]);

        User::create([
            'name'     => 'Dorm Manager',
            'email'    => 'manager@barca.com',     // Login: manager@barca.com / password
            'password' => Hash::make('password'),
            'role'     => 'admin',                 // <-- CRITICAL
        ]);

        // ── ROOMS ─────────────────────────────────────────────────
        $roomsData = [
            ['room_number' => 'A101', 'capacity' => 1, 'type' => 'single',    'gender' => 'male',   'floor' => '1st', 'building' => 'Block A', 'price_per_month' => 2500, 'status' => 'available', 'description' => 'Private single room with AC, study desk, and wardrobe.'],
            ['room_number' => 'A102', 'capacity' => 2, 'type' => 'double',    'gender' => 'male',   'floor' => '1st', 'building' => 'Block A', 'price_per_month' => 1800, 'status' => 'available', 'description' => 'Shared double room with individual desks and lockers.'],
            ['room_number' => 'A103', 'capacity' => 2, 'type' => 'double',    'gender' => 'female', 'floor' => '1st', 'building' => 'Block A', 'price_per_month' => 1800, 'status' => 'available', 'description' => 'Shared double room with individual desks and lockers.'],
            ['room_number' => 'A104', 'capacity' => 1, 'type' => 'single',    'gender' => 'female', 'floor' => '1st', 'building' => 'Block A', 'price_per_month' => 2500, 'status' => 'available', 'description' => 'Private single room with AC, study desk, and wardrobe.'],
            ['room_number' => 'A201', 'capacity' => 3, 'type' => 'triple',    'gender' => 'male',   'floor' => '2nd', 'building' => 'Block A', 'price_per_month' => 1400, 'status' => 'available', 'description' => 'Triple room with bunk beds and shared study area.'],
            ['room_number' => 'A202', 'capacity' => 3, 'type' => 'triple',    'gender' => 'female', 'floor' => '2nd', 'building' => 'Block A', 'price_per_month' => 1400, 'status' => 'available', 'description' => 'Triple room with bunk beds and shared study area.'],
            ['room_number' => 'B101', 'capacity' => 6, 'type' => 'dormitory', 'gender' => 'male',   'floor' => '1st', 'building' => 'Block B', 'price_per_month' => 900,  'status' => 'available', 'description' => 'Large dorm with 6 beds and communal study tables.'],
            ['room_number' => 'B102', 'capacity' => 6, 'type' => 'dormitory', 'gender' => 'female', 'floor' => '1st', 'building' => 'Block B', 'price_per_month' => 900,  'status' => 'available', 'description' => 'Large dorm with 6 beds and communal study tables.'],
            ['room_number' => 'B201', 'capacity' => 4, 'type' => 'dormitory', 'gender' => 'male',   'floor' => '2nd', 'building' => 'Block B', 'price_per_month' => 1100, 'status' => 'available', 'description' => 'Semi-private dorm with 4 beds.'],
            ['room_number' => 'B202', 'capacity' => 4, 'type' => 'dormitory', 'gender' => 'female', 'floor' => '2nd', 'building' => 'Block B', 'price_per_month' => 1100, 'status' => 'available', 'description' => 'Semi-private dorm with 4 beds.'],
            ['room_number' => 'C101', 'capacity' => 2, 'type' => 'double',    'gender' => 'mixed',  'floor' => '1st', 'building' => 'Block C', 'price_per_month' => 1800, 'status' => 'maintenance', 'description' => 'Under renovation.'],
        ];

        $rooms = [];
        foreach ($roomsData as $rd) {
            $rooms[$rd['room_number']] = Room::create($rd);
        }

        // ── STUDENT ACCOUNTS ──────────────────────────────────────
        // These accounts have role = 'student' → isStudent() returns true
        $studentsData = [
            ['name' => 'Juan dela Cruz',  'email' => 'juan@student.com',   'sn' => '2024-0001', 'course' => 'BS Computer Science', 'year' => 2, 'gender' => 'male',   'phone' => '09171234561'],
            ['name' => 'Maria Santos',    'email' => 'maria@student.com',  'sn' => '2024-0002', 'course' => 'BS Nursing',          'year' => 3, 'gender' => 'female', 'phone' => '09181234562'],
            ['name' => 'Pedro Reyes',     'email' => 'pedro@student.com',  'sn' => '2024-0003', 'course' => 'BS Engineering',     'year' => 1, 'gender' => 'male',   'phone' => '09191234563'],
            ['name' => 'Ana Garcia',      'email' => 'ana@student.com',    'sn' => '2024-0004', 'course' => 'BS Education',       'year' => 4, 'gender' => 'female', 'phone' => '09201234564'],
            ['name' => 'Carlos Mendoza',  'email' => 'carlos@student.com', 'sn' => '2024-0005', 'course' => 'BS Business Admin',  'year' => 2, 'gender' => 'male',   'phone' => '09211234565'],
            ['name' => 'Rosa Villanueva', 'email' => 'rosa@student.com',   'sn' => '2024-0006', 'course' => 'BS Psychology',      'year' => 1, 'gender' => 'female', 'phone' => '09221234566'],
        ];

        $students = [];
        foreach ($studentsData as $sd) {
            $user = User::create([
                'name'     => $sd['name'],
                'email'    => $sd['email'],
                'password' => Hash::make('password'),
                'role'     => 'student',               // <-- Always 'student' for students
            ]);
            $students[$sd['sn']] = Student::create([
                'user_id'           => $user->id,
                'student_number'    => $sd['sn'],
                'course'            => $sd['course'],
                'year_level'        => $sd['year'],
                'gender'            => $sd['gender'],
                'phone'             => $sd['phone'],
                'address'           => 'Sample Address, Philippines',
                'emergency_contact' => 'Parent - 09001234567',
            ]);
        }

        // ── ALLOCATIONS ───────────────────────────────────────────
        $allocData = [
            ['sn' => '2024-0001', 'room' => 'A102', 'date' => '2024-06-01', 'end' => '2025-05-31', 'status' => 'active', 'notes' => 'AY 2024-2025'],
            ['sn' => '2024-0002', 'room' => 'A103', 'date' => '2024-06-01', 'end' => '2025-05-31', 'status' => 'active', 'notes' => 'AY 2024-2025'],
        ];

        foreach ($allocData as $ad) {
            Allocation::create([
                'student_id'      => $students[$ad['sn']]->id,
                'room_id'         => $rooms[$ad['room']]->id,
                'allocation_date' => $ad['date'],
                'end_date'        => $ad['end'],
                'status'          => $ad['status'],
                'notes'           => $ad['notes'],
            ]);
        }

        // Sync room statuses
        Room::all()->each(function ($room) {
            if ($room->status === 'maintenance') return;
            $occupied = $room->activeAllocations()->count();
            $room->update([
                'status' => $occupied >= $room->capacity ? 'full' : 'available',
            ]);
        });
    }
}