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
        // Create platform_versions table
        Schema::create('platform_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('released_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['version']);
            $table->index(['is_active']);
            $table->index(['released_at']);
            $table->index(['created_at']);
        });

        // Create whats_new table (without title column as per the simplification)
        Schema::create('whats_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_version_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['feature', 'improvement', 'bugfix', 'security', 'deprecation'])->default('feature');
            $table->enum('status', ['draft', 'private', 'published'])->default('draft');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['platform_version_id']);
            $table->index(['type']);
            $table->index(['is_active']);
            $table->index(['sort_order']);
            $table->index(['created_at']);
        });

        // Create user_versions table
        Schema::create('user_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('version')->default('1.0.0');
            $table->string('last_seen_version')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id']);
            $table->index(['version']);
            $table->index(['last_seen_version']);
            $table->index(['updated_at']);
            
            // Unique constraint to ensure one record per user
            $table->unique(['user_id']);
        });

        // Add version column to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'version')) {
                $table->string('version')->default('1.0.0')->after('is_read_news');
                $table->index(['version']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove version column from users table
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'version')) {
                $table->dropIndex(['version']);
                $table->dropColumn('version');
            }
        });

        // Drop tables in reverse order (respecting foreign key constraints)
        Schema::dropIfExists('user_versions');
        Schema::dropIfExists('whats_new');
        Schema::dropIfExists('platform_versions');
    }
}; 