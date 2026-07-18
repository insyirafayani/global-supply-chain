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
        Schema::table('weather_data', function (Blueprint $table) {
            if (!Schema::hasColumn('weather_data', 'weather_code')) {
                $table->integer('weather_code')->nullable()->after('pressure');
            }
            if (!Schema::hasColumn('weather_data', 'timezone')) {
                $table->string('timezone')->nullable()->after('weather_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weather_data', function (Blueprint $table) {
            if (Schema::hasColumn('weather_data', 'weather_code')) {
                $table->dropColumn('weather_code');
            }
            if (Schema::hasColumn('weather_data', 'timezone')) {
                $table->dropColumn('timezone');
            }
        });
    }
};
