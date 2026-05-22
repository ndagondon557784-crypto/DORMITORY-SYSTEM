<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number', 'capacity', 'type', 'gender',
        'floor', 'building', 'price_per_month', 'description', 'status',
    ];

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocations()
    {
        return $this->hasMany(Allocation::class)->where('status', 'active');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getOccupancyAttribute(): int
    {
        return $this->activeAllocations()->count();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->capacity - $this->occupancy);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_slots === 0;
    }

    public function getOccupancyPercentAttribute(): int
    {
        return $this->capacity > 0
            ? (int) round(($this->occupancy / $this->capacity) * 100)
            : 0;
    }
}