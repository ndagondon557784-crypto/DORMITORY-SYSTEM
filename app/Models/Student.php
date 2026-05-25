<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'student_id', 'name', 'email', 'phone',
        'course', 'year_level', 'gender', 'status', 'photo'
    ];

    public function allocation(): HasOne {
        return $this->hasOne(Allocation::class)->where('status', 'Active')->latest();
    }

    public function allocations(): HasMany {
        return $this->hasMany(Allocation::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function currentRoom() {
        return $this->allocation?->room;
    }
}