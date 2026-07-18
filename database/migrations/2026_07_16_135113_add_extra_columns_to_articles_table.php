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
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'thumbnail')) {
                $table->string('thumbnail')->nullable();
            }
            if (!Schema::hasColumn('articles', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('articles', 'author')) {
                $table->string('author')->nullable();
            }
            if (!Schema::hasColumn('articles', 'status')) {
                $table->enum('status', ['Publish', 'Draft'])->default('Draft');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'thumbnail')) {
                $table->dropColumn('thumbnail');
            }
            if (Schema::hasColumn('articles', 'date')) {
                $table->dropColumn('date');
            }
            if (Schema::hasColumn('articles', 'author')) {
                $table->dropColumn('author');
            }
            if (Schema::hasColumn('articles', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
