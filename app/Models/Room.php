<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'dormitory_id', 'room_number', 'room_type', 'capacity',
        'current_occupancy', 'monthly_rate', 'floor_number',
        'status', 'amenities', 'description',
    ];

    protected $casts = [
        'monthly_rate' => 'decimal:2',
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'floor_number' => 'integer',
    ];

    public function dormitory()
    {
        return $this->belongsTo(Dormitory::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocations()
    {
        return $this->hasMany(Allocation::class)->where('status', 'active');
    }

    public function currentTenants()
    {
        return $this->hasManyThrough(Student::class, Allocation::class, 'room_id', 'id', 'id', 'student_id')
            ->where('allocations.status', 'active');
    }

    public function getIsAvailableAttribute(): bool
    {
        return in_array($this->status, ['available', 'occupied']) && $this->current_occupancy < $this->capacity;
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'available' => 'badge-success',
            'occupied' => 'badge-warning',
            'full' => 'badge-danger',
            'maintenance' => 'badge-secondary',
            'reserved' => 'badge-info',
            default => 'badge-secondary',
        };
    }

    public function updateOccupancy(): void
    {
        $count = $this->allocations()->where('status', 'active')->count();
        $this->current_occupancy = $count;

        if ($count === 0) {
            $this->status = 'available';
        } elseif ($count >= $this->capacity) {
            $this->status = 'full';
        } else {
            $this->status = 'occupied';
        }

        $this->save();
    }
}