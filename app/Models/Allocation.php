<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'room_id', 'allocated_by', 'check_in_date',
        'check_out_date', 'expected_check_out_date', 'status',
        'deposit_amount', 'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'expected_check_out_date' => 'date',
        'deposit_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function allocatedBy()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDurationInDaysAttribute(): int
    {
        $end = $this->check_out_date ?? now();
        return (int) $this->check_in_date->diffInDays($end);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'badge-success',
            'checked_out' => 'badge-secondary',
            'cancelled' => 'badge-danger',
            'expired' => 'badge-warning',
            default => 'badge-secondary',
        };
    }
}