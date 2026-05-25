<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Room;
use App\Models\Allocation;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create(['name' => 'Admin User', 'email' => 'admin@ndmu.edu.ph', 'password' => Hash::make('admin123'), 'role' => 'admin']);
        User::create(['name' => 'Staff User', 'email' => 'staff@ndmu.edu.ph', 'password' => Hash::make('staff123'), 'role' => 'staff']);

        // Rooms
        $r1 = Room::create(['room_number' => '101', 'floor' => 1, 'type' => 'Double', 'capacity' => 2, 'occupied' => 2, 'status' => 'Full', 'price_per_month' => 3500, 'amenities' => json_encode(['WiFi', 'AC', 'Private Bathroom'])]);
        $r2 = Room::create(['room_number' => '102', 'floor' => 1, 'type' => 'Single', 'capacity' => 1, 'occupied' => 0, 'status' => 'Available', 'price_per_month' => 4500, 'amenities' => json_encode(['WiFi', 'AC', 'Study Desk'])]);
        $r3 = Room::create(['room_number' => '201', 'floor' => 2, 'type' => 'Triple', 'capacity' => 3, 'occupied' => 1, 'status' => 'Available', 'price_per_month' => 2800, 'amenities' => json_encode(['WiFi', 'Shared Bathroom'])]);
        $r4 = Room::create(['room_number' => '202', 'floor' => 2, 'type' => 'Double', 'capacity' => 2, 'occupied' => 2, 'status' => 'Full', 'price_per_month' => 3500, 'amenities' => json_encode(['WiFi', 'AC'])]);
        $r5 = Room::create(['room_number' => '301', 'floor' => 3, 'type' => 'Quad', 'capacity' => 4, 'occupied' => 0, 'status' => 'Maintenance', 'price_per_month' => 2200, 'amenities' => json_encode(['WiFi'])]);
        $r6 = Room::create(['room_number' => '303', 'floor' => 3, 'type' => 'Single', 'capacity' => 1, 'occupied' => 1, 'status' => 'Full', 'price_per_month' => 4500, 'amenities' => json_encode(['WiFi', 'AC', 'Private Bathroom', 'Refrigerator'])]);

        // Students
        $s1 = Student::create(['student_id' => 'STU-2024-001', 'name' => 'Maria Santos', 'email' => 'maria@ndmu.edu.ph', 'phone' => '09171234567', 'course' => 'BS Nursing', 'year_level' => 2, 'gender' => 'Female', 'status' => 'Active']);
        $s2 = Student::create(['student_id' => 'STU-2024-002', 'name' => 'Juan dela Cruz', 'email' => 'juan@ndmu.edu.ph', 'phone' => '09281234567', 'course' => 'BS Engineering', 'year_level' => 3, 'gender' => 'Male', 'status' => 'Active']);
        $s3 = Student::create(['student_id' => 'STU-2024-003', 'name' => 'Ana Reyes', 'email' => 'ana@ndmu.edu.ph', 'phone' => '09391234567', 'course' => 'BS Education', 'year_level' => 1, 'gender' => 'Female', 'status' => 'Active']);
        $s4 = Student::create(['student_id' => 'STU-2024-004', 'name' => 'Pedro Bautista', 'email' => 'pedro@ndmu.edu.ph', 'phone' => '09501234567', 'course' => 'BS IT', 'year_level' => 4, 'gender' => 'Male', 'status' => 'Active']);
        $s5 = Student::create(['student_id' => 'STU-2024-005', 'name' => 'Rosa Mendoza', 'email' => 'rosa@ndmu.edu.ph', 'phone' => '09611234567', 'course' => 'BS Accountancy', 'year_level' => 2, 'gender' => 'Female', 'status' => 'Inactive']);
        $s6 = Student::create(['student_id' => 'STU-2024-006', 'name' => 'Carlo Villanueva', 'email' => 'carlo@ndmu.edu.ph', 'phone' => '09721234567', 'course' => 'BS Architecture', 'year_level' => 3, 'gender' => 'Male', 'status' => 'Active']);

        // Allocations
        Allocation::create(['student_id' => $s1->id, 'room_id' => $r1->id, 'start_date' => '2024-06-01', 'status' => 'Active']);
        Allocation::create(['student_id' => $s3->id, 'room_id' => $r1->id, 'start_date' => '2024-06-03', 'status' => 'Active']);
        Allocation::create(['student_id' => $s2->id, 'room_id' => $r4->id, 'start_date' => '2024-06-02', 'status' => 'Active']);
        Allocation::create(['student_id' => $s6->id, 'room_id' => $r4->id, 'start_date' => '2024-06-06', 'status' => 'Active']);
        Allocation::create(['student_id' => $s4->id, 'room_id' => $r6->id, 'start_date' => '2024-06-04', 'status' => 'Active']);

        // Payments
        Payment::create(['student_id' => $s1->id, 'room_id' => $r1->id, 'amount' => 3500, 'month' => 'June', 'year' => 2024, 'status' => 'Paid', 'paid_date' => '2024-06-05']);
        Payment::create(['student_id' => $s2->id, 'room_id' => $r4->id, 'amount' => 3500, 'month' => 'June', 'year' => 2024, 'status' => 'Paid', 'paid_date' => '2024-06-08']);
        Payment::create(['student_id' => $s3->id, 'room_id' => $r1->id, 'amount' => 3500, 'month' => 'June', 'year' => 2024, 'status' => 'Pending']);
        Payment::create(['student_id' => $s4->id, 'room_id' => $r6->id, 'amount' => 4500, 'month' => 'June', 'year' => 2024, 'status' => 'Overdue']);
        Payment::create(['student_id' => $s6->id, 'room_id' => $r4->id, 'amount' => 3500, 'month' => 'June', 'year' => 2024, 'status' => 'Paid', 'paid_date' => '2024-06-10']);
    }
}