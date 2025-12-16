<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('whats_new', function (Blueprint $table): void {
            if (!Schema::hasColumn('whats_new', 'title')) {
                $table->string('title')->after('platform_version_id');
            }
            if (!Schema::hasColumn('whats_new', 'status')) {
                $table->enum('status', ['draft', 'private', 'published'])->default('draft')->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whats_new', function (Blueprint $table): void {
            $table->dropColumn(['title', 'status']);
        });
    }
};
