<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dormitory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'address', 'contact_number', 'email',
        'description', 'total_capacity', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    public function getOccupiedRoomsCountAttribute(): int
    {
        return $this->rooms()->whereIn('status', ['occupied', 'full'])->count();
    }

    public function getTotalStudentsAttribute(): int
    {
        return $this->rooms()->withCount(['allocations' => function($q) {
            $q->where('status', 'active');
        }])->get()->sum('allocations_count');
    }
}