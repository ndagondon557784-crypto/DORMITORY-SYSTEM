<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Allocation;
use App\Models\ActivityLog;

class PaymentService
{
    public function record(Allocation $allocation, array $data): Payment
    {
        $payment = Payment::create([
            'allocation_id'    => $allocation->id,
            'student_id'       => $allocation->student_id,
            'reference_number' => Payment::generateReference(),
            'amount'           => $data['amount'],
            'payment_date'     => $data['payment_date'],
            'period_from'      => $data['period_from'],
            'period_to'        => $data['period_to'],
            'payment_method'   => $data['payment_method'],
            'status'           => 'paid',
            'notes'            => $data['notes'] ?? null,
            'recorded_by'      => auth()->id(),
        ]);

        ActivityLog::log('payment', "Recorded payment of ₱{$payment->amount} for {$allocation->student->full_name}", 'Payment', $payment->id);

        return $payment;
    }
}