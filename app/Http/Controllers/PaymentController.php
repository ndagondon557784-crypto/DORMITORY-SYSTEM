<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request) {
        $query = Payment::with(['student', 'room']);

        if ($request->search) {
            $query->whereHas('student', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        if ($request->status && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        $summary = [
            'collected' => Payment::where('status', 'Paid')->sum('amount'),
            'pending'   => Payment::where('status', 'Pending')->sum('amount'),
            'overdue'   => Payment::where('status', 'Overdue')->sum('amount'),
        ];

        $students = Student::where('status', 'Active')->with('allocation.room')->get();

        return view('payments.index', compact('payments', 'summary', 'students'));
    }

    public function store(Request $request) {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id'    => 'required|exists:rooms,id',
            'amount'     => 'required|numeric|min:1',
            'month'      => 'required|string',
            'year'       => 'required|integer',
            'status'     => 'required|in:Paid,Pending,Overdue',
        ]);

        $data = $request->only(['student_id', 'room_id', 'amount', 'month', 'year', 'status', 'notes']);
        if ($data['status'] === 'Paid') {
            $data['paid_date'] = now()->toDateString();
        }

        Payment::create($data);
        return back()->with('success', 'Payment record added.');
    }

    public function markPaid(string $id) {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'Paid', 'paid_date' => now()->toDateString()]);
        return back()->with('success', 'Payment marked as paid.');
    }

    public function destroy(Payment $payment) {
        $payment->delete();
        return back()->with('success', 'Payment deleted.');
    }
}