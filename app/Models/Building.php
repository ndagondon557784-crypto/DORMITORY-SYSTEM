<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'total_floors', 'description', 'gender_type', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    public function getTotalCapacityAttribute(): int
    {
        return $this->rooms()->sum('capacity');
    }

    public function getCurrentOccupancyAttribute(): int
    {
        return $this->rooms()->sum('current_occupancy');
    }
}