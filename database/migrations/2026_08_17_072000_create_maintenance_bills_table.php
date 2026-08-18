<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flat_id')->constrained('flats')->onDelete('cascade');
            $table->string('bill_number')->unique();
            $table->date('billing_month');
            $table->decimal('amount_due', 10, 2)->default(0);
            $table->decimal('water_charges', 10, 2)->default(0);
            $table->decimal('security_charges', 10, 2)->default(0);
            $table->decimal('repair_charges', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->decimal('penalty', 10, 2)->default(0);
            $table->date('due_date');
            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_bills');
    }
};