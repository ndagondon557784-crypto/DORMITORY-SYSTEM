<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'floor', 'type', 'capacity',
        'occupied', 'status', 'price_per_month', 'amenities'
    ];

    protected $casts = ['amenities' => 'array'];

    public function allocations(): HasMany {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocations(): HasMany {
        return $this->hasMany(Allocation::class)->where('status', 'Active');
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function updateOccupancy(): void {
        $count = $this->activeAllocations()->count();
        $this->occupied = $count;
        $this->status = $count >= $this->capacity ? 'Full' : ($this->status === 'Maintenance' ? 'Maintenance' : 'Available');
        $this->save();
    }
}