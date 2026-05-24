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
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->string('room_number')->unique();
            $table->integer('capacity')->default(1);
            $table->integer('current_occupancy')->default(0);
            $table->decimal('monthly_rent', 10, 2);
            $table->string('type')->default('single'); // single, double, triple
            $table->string('status')->default('available'); // available, occupied, maintenance
            $table->text('amenities')->nullable();
            $table->string('floor')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};