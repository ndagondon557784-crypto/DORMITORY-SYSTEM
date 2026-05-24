<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Allocation;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with('student.user', 'allocation')
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function create(): View
    {
        $students = Student::with('user', 'currentAllocation')->get();
        return view('payments.create', compact('students'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $payment = Payment::create($request->validated());

        $student = $payment->student;
        if ($student->outstanding_balance > 0) {
            $student->outstanding_balance = max(0, $student->outstanding_balance - $payment->amount);
            $student->save();
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully');
    }

    public function show(Payment $payment): View
    {
        $payment->load('student.user', 'allocation');
        return view('payments.show', compact('payment'));
    }

    public function receipt(Payment $payment)
    {
        return view('payments.receipt', compact('payment'));
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $student = $payment->student;
        if ($payment->status === 'completed') {
            $student->outstanding_balance += $payment->amount;
            $student->save();
        }

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully');
    }

    public function studentPayments(Student $student): View
    {
        $payments = $student->payments()
            ->with('allocation')
            ->paginate(10);

        return view('payments.student-payments', compact('student', 'payments'));
    }

    public function search()
    {
        $query = request()->input('query');
        $payments = Payment::whereHas('student.user', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%");
        })
            ->orWhere('reference_number', 'like', "%{$query}%")
            ->with('student.user', 'allocation')
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }
}