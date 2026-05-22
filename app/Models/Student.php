<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'student_number', 'course',
        'year_level', 'gender', 'phone', 'address', 'emergency_contact',
    ];

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

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function pendingApplication()
    {
        return $this->hasOne(Application::class)
                    ->where('status', 'pending')
                    ->latest();
    }

    public function latestApplication()
    {
        return $this->hasOne(Application::class)->latest();
    }
}