<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDaysOccupiedAttribute()
    {
        $endDate = $this->check_out_date ?? now();
        return $this->check_in_date->diffInDays($endDate);
    }

    public function getTotalChargeAttribute()
    {
        return ($this->days_occupied / 30) * $this->room->monthly_rent;
    }
}