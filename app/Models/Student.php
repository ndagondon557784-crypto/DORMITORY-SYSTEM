<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'student_id', 'full_name', 'email', 'phone', 'gender',
        'date_of_birth', 'course', 'year_level', 'address',
        'emergency_contact_name', 'emergency_contact_phone', 'photo', 'status'
    ];

    protected $casts = ['date_of_birth' => 'date'];

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
        return $this->hasOne(Allocation::class)->where('status', 'active')->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function currentRoom()
    {
        return $this->activeAllocation?->room;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }
}