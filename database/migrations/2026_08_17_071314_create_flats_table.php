<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->string('block_name');
            $table->string('flat_number');
            $table->string('floor')->nullable();
            $table->enum('occupancy_type', ['owner', 'tenant'])->nullable();
            $table->enum('status', ['occupied', 'vacant'])->default('vacant');
            $table->timestamps();

            $table->unique(['block_name', 'flat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};