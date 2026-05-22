<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'first_name', 'last_name', 'email', 'phone',
        'date_of_birth', 'gender', 'course', 'year_level',
        'home_address', 'emergency_contact_name', 'emergency_contact_phone',
        'emergency_contact_relation', 'avatar', 'status', 'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'year_level' => 'integer',
    ];

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocation()
    {
        return $this->hasOne(Allocation::class)->where('status', 'active')->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $bg = $this->gender === 'female' ? 'ec4899' : '6366f1';
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . "&background={$bg}&color=fff&size=100";
    }

    public function getCurrentRoomAttribute()
    {
        return $this->activeAllocation?->room;
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'graduated' => 'badge-info',
            'suspended' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}