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
        if (!Schema::hasColumn('countries', 'iso3')) {
            Schema::table('countries', function (Blueprint $table) {
                $table->string('iso3')->nullable()->after('iso2');
            });
        }
    }

public function down(): void
{
    Schema::table('countries', function (Blueprint $table) {
        if (Schema::hasColumn('countries', 'iso3')) {
            $table->dropColumn('iso3');
        }
    });
}
};
