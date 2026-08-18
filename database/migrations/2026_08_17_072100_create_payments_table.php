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
            $table->foreignId('bill_id')->constrained('maintenance_bills')->onDelete('cascade');
            $table->foreignId('resident_id')->constrained('resident_profiles')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['simulated_card', 'simulated_bank', 'cash'])->default('simulated_card');
            $table->string('transaction_reference')->unique();
            $table->enum('payment_status', ['pending', 'successful', 'failed'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};