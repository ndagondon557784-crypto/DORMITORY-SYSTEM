<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id', 'room_id', 'amount',
        'month', 'year', 'status', 'paid_date', 'notes'
    ];

    protected $casts = ['paid_date' => 'date'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }

    public function room(): BelongsTo {
        return $this->belongsTo(Room::class);
    }
}