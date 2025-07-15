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
        Schema::table('whats_new', function (Blueprint $table) {
            // Remove the title column as we'll use content for everything
            $table->dropColumn('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whats_new', function (Blueprint $table) {
            // Add back the title column
            $table->string('title')->after('platform_version_id');
        });
    }
}; 