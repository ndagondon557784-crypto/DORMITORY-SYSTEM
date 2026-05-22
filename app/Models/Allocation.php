<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'room_id', 'allocation_date', 'end_date', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'allocation_date' => 'date',
            'end_date'        => 'date',
        ];
    }

    public function student() { return $this->belongsTo(Student::class); }
    public function room()    { return $this->belongsTo(Room::class); }
}