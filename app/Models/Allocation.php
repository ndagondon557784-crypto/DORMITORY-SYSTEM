<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Allocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id', 'room_id', 'assigned_by', 'check_in_date',
        'check_out_date', 'actual_check_out', 'status', 'notes'
    ];

    protected $casts = [
        'check_in_date'    => 'date',
        'check_out_date'   => 'date',
        'actual_check_out' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDurationDaysAttribute(): int
    {
        $end = $this->actual_check_out ?? now();
        return (int) $this->check_in_date->diffInDays($end);
    }
}