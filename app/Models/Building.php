<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'total_floors',
        'description',
        'gender_type',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'total_floors' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    public function getTotalCapacityAttribute(): int
    {
        return (int) $this->rooms()->sum('capacity');
    }

    public function getCurrentOccupancyAttribute(): int
    {
        return (int) $this->rooms()->sum('current_occupancy');
    }

    public function getOccupancyRateAttribute(): float
    {
        $total = $this->total_capacity;
        if ($total === 0) return 0.0;
        return round(($this->current_occupancy / $total) * 100, 1);
    }
}