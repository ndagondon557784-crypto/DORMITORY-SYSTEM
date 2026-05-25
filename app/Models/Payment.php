<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_id', 'student_id', 'reference_number', 'amount',
        'payment_date', 'period_from', 'period_to', 'payment_method',
        'status', 'receipt_path', 'notes', 'recorded_by'
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'payment_date' => 'date',
        'period_from'  => 'date',
        'period_to'    => 'date',
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'PAY-' . strtoupper(uniqid());
        } while (self::where('reference_number', $ref)->exists());
        return $ref;
    }
}