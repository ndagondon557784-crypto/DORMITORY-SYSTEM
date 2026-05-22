<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ActivityLog, Allocation, Payment, Student};
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'allocation.room', 'receivedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                    ->orWhereHas('student', fn($s) => $s->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('month')) {
            $query->where('period_month', $request->month);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        $totalPaid = Payment::where('status', 'paid')->sum('amount');
        $totalPending = Payment::where('status', 'pending')->sum('amount');
        $totalOverdue = Payment::where('status', 'overdue')->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalPaid', 'totalPending', 'totalOverdue'));
    }

    public function create()
    {
        $allocations = Allocation::with(['student', 'room'])
            ->where('status', 'active')
            ->get();

        return view('admin.payments.create', compact('allocations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'allocation_id' => 'required|exists:allocations,id',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:monthly_rent,deposit,utility,penalty,other',
            'payment_method' => 'required|in:cash,bank_transfer,gcash,maya,check',
            'status' => 'required|in:paid,pending,overdue',
            'payment_date' => 'required|date',
            'due_date' => 'nullable|date',
            'period_month' => 'nullable|string',
            'notes' => 'nullable|string',
            'receipt_number' => 'nullable|string',
        ]);

        $allocation = Allocation::findOrFail($validated['allocation_id']);
        $validated['student_id'] = $allocation->student_id;
        $validated['received_by'] = auth()->id();
        $validated['payment_reference'] = Payment::generateReference();

        $payment = Payment::create($validated);
        ActivityLog::record('create', "Recorded payment {$payment->payment_reference} - ₱{$payment->amount}", $payment);

        return redirect()->route('admin.payments.index')
            ->with('success', "Payment recorded successfully. Reference: {$payment->payment_reference}");
    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'allocation.room.dormitory', 'receivedBy']);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $allocations = Allocation::with(['student', 'room'])->get();
        return view('admin.payments.edit', compact('payment', 'allocations'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:monthly_rent,deposit,utility,penalty,other',
            'payment_method' => 'required|in:cash,bank_transfer,gcash,maya,check',
            'status' => 'required|in:paid,pending,overdue,cancelled',
            'payment_date' => 'required|date',
            'due_date' => 'nullable|date',
            'period_month' => 'nullable|string',
            'notes' => 'nullable|string',
            'receipt_number' => 'nullable|string',
        ]);

        $old = $payment->toArray();
        $payment->update($validated);
        ActivityLog::record('update', "Updated payment {$payment->payment_reference}", $payment, $old, $payment->fresh()->toArray());

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $ref = $payment->payment_reference;
        ActivityLog::record('delete', "Deleted payment {$ref}", $payment);
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', "Payment {$ref} deleted successfully.");
    }

    public function getStudentAllocations(Student $student)
    {
        $allocation = $student->activeAllocation()->with('room')->first();
        return response()->json($allocation);
    }
}