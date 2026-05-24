<?php

namespace Database\Seeders;

use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@dormms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '09171234567',
            'is_active' => true,
        ]);

        // Staff
        User::create([
            'name' => 'Maria Santos',
            'email' => 'staff@dormms.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '09281234567',
            'is_active' => true,
        ]);

        // Student User
        $studentUser = User::create([
            'name' => 'Juan dela Cruz',
            'email' => 'student@dormms.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '09391234567',
            'is_active' => true,
        ]);

        // Rooms
        $rooms = [
            ['room_number' => '101', 'building' => 'Block A', 'floor' => 1, 'type' => 'single', 'capacity' => 1, 'monthly_rate' => 4500, 'gender_type' => 'male', 'status' => 'available', 'has_aircon' => true, 'has_wifi' => true, 'has_bathroom' => false, 'has_study_desk' => true],
            ['room_number' => '102', 'building' => 'Block A', 'floor' => 1, 'type' => 'double', 'capacity' => 2, 'monthly_rate' => 3500, 'gender_type' => 'male', 'status' => 'available', 'has_aircon' => false, 'has_wifi' => true, 'has_bathroom' => false, 'has_study_desk' => true],
            ['room_number' => '103', 'building' => 'Block A', 'floor' => 1, 'type' => 'triple', 'capacity' => 3, 'monthly_rate' => 2800, 'gender_type' => 'male', 'status' => 'available', 'has_aircon' => false, 'has_wifi' => true, 'has_bathroom' => false, 'has_study_desk' => true],
            ['room_number' => '201', 'building' => 'Block B', 'floor' => 2, 'type' => 'single', 'capacity' => 1, 'monthly_rate' => 5000, 'gender_type' => 'female', 'status' => 'available', 'has_aircon' => true, 'has_wifi' => true, 'has_bathroom' => true, 'has_study_desk' => true],
            ['room_number' => '202', 'building' => 'Block B', 'floor' => 2, 'type' => 'double', 'capacity' => 2, 'monthly_rate' => 3800, 'gender_type' => 'female', 'status' => 'available', 'has_aircon' => true, 'has_wifi' => true, 'has_bathroom' => false, 'has_study_desk' => true],
            ['room_number' => '203', 'building' => 'Block B', 'floor' => 2, 'type' => 'quad', 'capacity' => 4, 'monthly_rate' => 2500, 'gender_type' => 'female', 'status' => 'available', 'has_aircon' => false, 'has_wifi' => true, 'has_bathroom' => false, 'has_study_desk' => false],
            ['room_number' => '301', 'building' => 'Block C', 'floor' => 3, 'type' => 'suite', 'capacity' => 2, 'monthly_rate' => 7500, 'gender_type' => 'mixed', 'status' => 'available', 'has_aircon' => true, 'has_wifi' => true, 'has_bathroom' => true, 'has_study_desk' => true],
            ['room_number' => '302', 'building' => 'Block C', 'floor' => 3, 'type' => 'double', 'capacity' => 2, 'monthly_rate' => 3200, 'gender_type' => 'mixed', 'status' => 'maintenance', 'has_aircon' => false, 'has_wifi' => false, 'has_bathroom' => false, 'has_study_desk' => true],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        // Students
        $student1 = Student::create([
            'user_id' => $studentUser->id,
            'student_id' => 'STU-2024-001',
            'first_name' => 'Juan',
            'last_name' => 'dela Cruz',
            'email' => 'student@dormms.com',
            'phone' => '09391234567',
            'gender' => 'male',
            'date_of_birth' => '2002-05-15',
            'course' => 'BS Computer Science',
            'year_level' => 3,
            'address' => '123 Rizal Street, Manila',
            'guardian_name' => 'Pedro dela Cruz',
            'guardian_phone' => '09175551234',
            'guardian_relationship' => 'Father',
            'status' => 'active',
        ]);

        $student2 = Student::create([
            'student_id' => 'STU-2024-002',
            'first_name' => 'Maria',
            'last_name' => 'Reyes',
            'email' => 'maria.reyes@example.com',
            'phone' => '09281234568',
            'gender' => 'female',
            'date_of_birth' => '2003-08-20',
            'course' => 'BS Nursing',
            'year_level' => 2,
            'address' => '456 Bonifacio Ave, Quezon City',
            'guardian_name' => 'Rosa Reyes',
            'guardian_phone' => '09181234568',
            'guardian_relationship' => 'Mother',
            'status' => 'active',
        ]);

        $student3 = Student::create([
            'student_id' => 'STU-2024-003',
            'first_name' => 'Carlo',
            'last_name' => 'Mendoza',
            'email' => 'carlo.mendoza@example.com',
            'phone' => '09391234569',
            'gender' => 'male',
            'date_of_birth' => '2001-12-01',
            'course' => 'BS Engineering',
            'year_level' => 4,
            'address' => '789 Mabini Street, Makati',
            'guardian_name' => 'Ana Mendoza',
            'guardian_phone' => '09201234569',
            'guardian_relationship' => 'Mother',
            'status' => 'active',
        ]);

        // Allocations
        $room1 = Room::where('room_number', '102')->first();
        $allocation1 = Allocation::create([
            'student_id' => $student1->id,
            'room_id' => $room1->id,
            'assigned_by' => 1,
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
            'check_in_date' => '2024-01-02',
            'status' => 'active',
            'allocation_type' => 'manual',
        ]);
        $room1->update(['status' => 'occupied']);

        $room2 = Room::where('room_number', '201')->first();
        $allocation2 = Allocation::create([
            'student_id' => $student2->id,
            'room_id' => $room2->id,
            'assigned_by' => 1,
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
            'check_in_date' => '2024-01-03',
            'status' => 'active',
            'allocation_type' => 'automatic',
        ]);
        $room2->update(['status' => 'occupied']);

        // Payments
        Payment::create([
            'allocation_id' => $allocation1->id,
            'student_id' => $student1->id,
            'reference_number' => 'PAY-AABB1122-2024',
            'amount' => 3500,
            'penalty' => 0,
            'discount' => 0,
            'total_amount' => 3500,
            'payment_type' => 'monthly',
            'payment_method' => 'gcash',
            'status' => 'verified',
            'payment_date' => '2024-01-05',
            'due_date' => '2024-01-05',
            'period_covered' => 'January 2024',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        Payment::create([
            'allocation_id' => $allocation2->id,
            'student_id' => $student2->id,
            'reference_number' => 'PAY-CCDD3344-2024',
            'amount' => 5000,
            'penalty' => 0,
            'discount' => 200,
            'total_amount' => 4800,
            'payment_type' => 'monthly',
            'payment_method' => 'bank_transfer',
            'status' => 'pending',
            'payment_date' => '2024-01-08',
            'due_date' => '2024-01-05',
            'period_covered' => 'January 2024',
        ]);

        // Settings
        $defaults = [
            ['key' => 'app_name', 'value' => 'DormMS', 'group' => 'general'],
            ['key' => 'app_address', 'value' => 'University Campus, Manila, Philippines', 'group' => 'general'],
            ['key' => 'app_phone', 'value' => '(02) 8123-4567', 'group' => 'general'],
            ['key' => 'app_email', 'value' => 'dorm@university.edu.ph', 'group' => 'general'],
            ['key' => 'late_fee_percent', 'value' => '5', 'group' => 'payment'],
            ['key' => 'grace_period_days', 'value' => '5', 'group' => 'payment'],
            ['key' => 'semester_months', 'value' => '6', 'group' => 'academic'],
            ['key' => 'deposit_months', 'value' => '2', 'group' => 'payment'],
        ];

        foreach ($defaults as $s) {
            Setting::create($s);
        }
    }
}