<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'room_number',
        'floor',
        'room_type',
        'capacity',
        'current_occupancy',
        'monthly_rate',
        'status',
        'amenities',
        'description',
    ];

    protected $casts = [
        'monthly_rate'      => 'decimal:2',
        'capacity'          => 'integer',
        'current_occupancy' => 'integer',
        'floor'             => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

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

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getIsAvailableAttribute(): bool
    {
        return $this->current_occupancy < $this->capacity
            && $this->status !== 'maintenance';
    }

    public function getOccupancyPercentageAttribute(): int
    {
        if ($this->capacity === 0) {
            return 0;
        }

        return (int) round(($this->current_occupancy / $this->capacity) * 100);
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }

    // ── Methods ───────────────────────────────────────────────────────────────

    public function updateStatus(): void
    {
        // Never override a maintenance status
        if ($this->status === 'maintenance') {
            return;
        }

        if ($this->current_occupancy >= $this->capacity) {
            $this->update(['status' => 'full']);
        } elseif ($this->current_occupancy > 0) {
            $this->update(['status' => 'occupied']);
        } else {
            $this->update(['status' => 'available']);
        }
    }
}