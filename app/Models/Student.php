<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_id',
        'full_name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'course',
        'year_level',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'photo',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function activeAllocation()
    {
        return $this->hasOne(Allocation::class)
                    ->where('status', 'active')
                    ->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return 'https://ui-avatars.com/api/?name='
            . urlencode($this->full_name)
            . '&background=004d98&color=fff&bold=true';
    }

    public function getCurrentRoomAttribute()
    {
        return $this->activeAllocation?->room;
    }
}