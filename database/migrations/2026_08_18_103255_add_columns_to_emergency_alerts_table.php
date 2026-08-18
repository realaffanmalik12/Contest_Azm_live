<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('emergency_alerts', function (Blueprint $table) {
            if (!Schema::hasColumn('emergency_alerts', 'alert_type')) {
                $table->string('alert_type', 100)->nullable()->after('title');
            }
            if (!Schema::hasColumn('emergency_alerts', 'severity')) {
                $table->string('severity', 50)->default('warning')->after('alert_type');
            }
            if (!Schema::hasColumn('emergency_alerts', 'description')) {
                $table->text('description')->nullable()->after('severity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_alerts', function (Blueprint $table) {
            $table->dropColumn(['alert_type', 'severity', 'description']);
        });
    }
};
