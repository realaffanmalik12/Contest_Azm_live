<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_profile_id')->constrained('resident_profiles')->onDelete('cascade');
            $table->string('name');
            $table->string('relationship')->nullable();
            $table->enum('type', ['family', 'tenant'])->default('family');
            $table->string('cnic')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};