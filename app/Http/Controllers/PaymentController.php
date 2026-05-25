<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Student;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $query = Payment::with(['student', 'allocation.room'])
            ->when($request->search, fn($q, $s) => $q->where('reference_number', 'like', "%{$s}%")
                ->orWhereHas('student', fn($q) => $q->where('full_name', 'like', "%{$s}%")))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->month, fn($q, $m) => $q->whereMonth('payment_date', $m))
            ->when($request->year, fn($q, $y) => $q->whereYear('payment_date', $y));

        $payments = $query->latest()->paginate(15)->withQueryString();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        return view('payments.index', compact('payments', 'totalRevenue'));
    }

    public function create(Request $request)
    {
        $allocation = null;
        if ($request->allocation_id) {
            $allocation = Allocation::with(['student', 'room'])->findOrFail($request->allocation_id);
        }

        $activeAllocations = Allocation::with(['student', 'room'])
            ->where('status', 'active')
            ->get();

        return view('payments.create', compact('allocation', 'activeAllocations'));
    }

    public function store(StorePaymentRequest $request)
    {
        $allocation = Allocation::findOrFail($request->allocation_id);

        $payment = $this->paymentService->record($allocation, $request->validated());

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'allocation.room.building', 'recordedBy']);
        return view('payments.show', compact('payment'));
    }
}