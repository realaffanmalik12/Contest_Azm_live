<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('resident_profiles')->onDelete('cascade');
            $table->foreignId('flat_id')->constrained('flats')->onDelete('cascade');
            $table->string('visitor_name');
            $table->string('phone')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->enum('visitor_type', ['guest', 'delivery', 'cab', 'vendor'])->default('guest');
            $table->string('gate_pass_code')->unique();
            $table->string('qr_token')->unique();
            $table->dateTime('valid_from');
            $table->dateTime('valid_until');
            $table->enum('status', ['pending', 'active', 'used', 'expired', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};