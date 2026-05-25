<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->date('period_from');
            $table->date('period_to');
            $table->enum('payment_method', ['cash', 'gcash', 'bank_transfer', 'check'])->default('cash');
            $table->enum('status', ['paid', 'pending', 'overdue', 'cancelled'])->default('pending');
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};