<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'building_id',
        'room_number',
        'capacity',
        'current_occupancy',
        'monthly_rent',
        'type',
        'status',
        'amenities',
        'floor',
        'is_active',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function currentAllocations()
    {
        return $this->hasMany(Allocation::class)
            ->where('status', 'active');
    }

    public function getAvailableSpacesAttribute()
    {
        return $this->capacity - $this->current_occupancy;
    }

    public function isAvailable()
    {
        return $this->status === 'available' && $this->available_spaces > 0;
    }
}