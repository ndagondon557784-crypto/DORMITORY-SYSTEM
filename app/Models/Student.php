<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_id',
        'course',
        'year',
        'phone',
        'date_of_birth',
        'address',
        'guardian_name',
        'guardian_contact',
        'outstanding_balance',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'outstanding_balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function currentAllocation()
    {
        return $this->hasOne(Allocation::class)
            ->where('status', 'active')
            ->latest('check_in_date');
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }
}