<?php

namespace App\Services;

use App\Models\Allocation;
use App\Models\ActivityLog;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Exception;

class AllocationService
{
    public function allocate(Student $student, Room $room, array $data): Allocation
    {
        if (!$room->is_available) {
            throw new Exception('Room is not available for allocation.');
        }

        if ($student->activeAllocation) {
            throw new Exception('Student already has an active room allocation.');
        }

        return DB::transaction(function () use ($student, $room, $data) {
            $allocation = Allocation::create([
                'student_id'     => $student->id,
                'room_id'        => $room->id,
                'assigned_by'    => auth()->id(),
                'check_in_date'  => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'] ?? null,
                'status'         => 'active',
                'notes'          => $data['notes'] ?? null,
            ]);

            $room->increment('current_occupancy');
            $room->updateStatus();

            ActivityLog::log('allocate', "Allocated {$student->full_name} to Room {$room->room_number}", 'Allocation', $allocation->id);

            return $allocation;
        });
    }

    public function checkOut(Allocation $allocation, ?string $notes = null): void
    {
        DB::transaction(function () use ($allocation, $notes) {
            $allocation->update([
                'status'           => 'checked_out',
                'actual_check_out' => now()->toDateString(),
                'notes'            => $notes ?? $allocation->notes,
            ]);

            $room = $allocation->room;
            $room->decrement('current_occupancy');
            $room->updateStatus();

            ActivityLog::log('checkout', "Checked out {$allocation->student->full_name} from Room {$room->room_number}", 'Allocation', $allocation->id);
        });
    }
}