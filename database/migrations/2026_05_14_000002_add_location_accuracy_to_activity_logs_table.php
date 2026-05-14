<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->decimal('location_accuracy', 8, 2)->nullable()->after('longitude');
            $table->string('location_source', 3)->nullable()->after('location_accuracy'); // 'gps' | 'ip'
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['location_accuracy', 'location_source']);
        });
    }
};
