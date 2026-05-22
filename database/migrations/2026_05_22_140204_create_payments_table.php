<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['monthly_rent', 'deposit', 'utility', 'penalty', 'other'])->default('monthly_rent');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'gcash', 'maya', 'check'])->default('cash');
            $table->enum('status', ['paid', 'pending', 'overdue', 'cancelled'])->default('paid');
            $table->date('payment_date');
            $table->date('due_date')->nullable();
            $table->string('period_month')->nullable(); // e.g., "2024-01"
            $table->text('notes')->nullable();
            $table->string('receipt_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};