<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->integer('floor')->default(1);
            $table->enum('room_type', ['single', 'double', 'triple', 'quad'])->default('double');
            $table->integer('capacity')->default(2);
            $table->integer('current_occupancy')->default(0);
            $table->decimal('monthly_rate', 10, 2)->default(0);
            $table->enum('status', ['available', 'occupied', 'full', 'maintenance'])->default('available');
            $table->text('amenities')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['building_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};