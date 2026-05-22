<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dormitory_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->enum('room_type', ['single', 'double', 'triple', 'quad', 'suite'])->default('single');
            $table->integer('capacity')->default(1);
            $table->integer('current_occupancy')->default(0);
            $table->decimal('monthly_rate', 10, 2);
            $table->integer('floor_number')->default(1);
            $table->enum('status', ['available', 'occupied', 'full', 'maintenance', 'reserved'])->default('available');
            $table->text('amenities')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['dormitory_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};