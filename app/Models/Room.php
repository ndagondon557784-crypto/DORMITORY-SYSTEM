<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id', 'room_number', 'floor', 'room_type',
        'capacity', 'current_occupancy', 'monthly_rate', 'status', 'amenities', 'description'
    ];

    protected $casts = ['monthly_rate' => 'decimal:2'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocations()
    {
        return $this->hasMany(Allocation::class)->where('status', 'active');
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->current_occupancy < $this->capacity && $this->status !== 'maintenance';
    }

    public function getOccupancyPercentageAttribute(): int
    {
        if ($this->capacity === 0) return 0;
        return (int) round(($this->current_occupancy / $this->capacity) * 100);
    }

    public function updateStatus(): void
    {
        if ($this->status === 'maintenance') return;

        if ($this->current_occupancy >= $this->capacity) {
            $this->update(['status' => 'full']);
        } elseif ($this->current_occupancy > 0) {
            $this->update(['status' => 'occupied']);
        } else {
            $this->update(['status' => 'available']);
        }
    }
}