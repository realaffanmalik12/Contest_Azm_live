<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['general', 'emergency', 'maintenance', 'event', 'important'])->default('general');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->foreignId('published_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('published_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->enum('status', ['draft', 'published', 'expired', 'archived'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};