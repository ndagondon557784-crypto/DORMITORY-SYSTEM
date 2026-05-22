<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_id', 'student_id', 'received_by', 'payment_reference',
        'amount', 'payment_type', 'payment_method', 'status',
        'payment_date', 'due_date', 'period_month', 'notes', 'receipt_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'due_date' => 'date',
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public static function generateReference(): string
    {
        return 'PAY-' . strtoupper(uniqid());
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid' => 'badge-success',
            'pending' => 'badge-warning',
            'overdue' => 'badge-danger',
            'cancelled' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    public function getPaymentTypeLabel(): string
    {
        return match($this->payment_type) {
            'monthly_rent' => 'Monthly Rent',
            'deposit' => 'Security Deposit',
            'utility' => 'Utility Bill',
            'penalty' => 'Penalty Fee',
            'other' => 'Other',
            default => ucfirst($this->payment_type),
        };
    }
}