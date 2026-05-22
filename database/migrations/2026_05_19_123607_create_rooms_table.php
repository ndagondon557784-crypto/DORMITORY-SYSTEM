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
            $table->string('room_number', 20)->unique();
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->enum('type', ['single', 'double', 'triple', 'dormitory']);
            $table->enum('gender', ['male', 'female', 'mixed'])->default('mixed');
            $table->string('floor', 50)->nullable();
            $table->string('building', 100)->nullable();
            $table->decimal('price_per_month', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'full', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};