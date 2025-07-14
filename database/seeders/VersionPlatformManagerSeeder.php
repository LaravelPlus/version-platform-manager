<?php

namespace LaravelPlus\VersionPlatformManager\Database\Seeders;

use Illuminate\Database\Seeder;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;

class VersionPlatformManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create initial platform version
        $version = PlatformVersion::create([
            'version' => '1.0.0',
            'title' => 'Initial Release',
            'description' => 'Welcome to our platform! This is the initial release with core features.',
            'is_active' => true,
            'released_at' => now(),
        ]);

        // Create what's new content for the initial version
        WhatsNew::create([
            'platform_version_id' => $version->id,
            'title' => 'Welcome to Our Platform',
            'content' => 'We\'re excited to introduce our new platform with enhanced features and improved user experience.',
            'type' => 'feature',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Create version 1.1.0
        $version110 = PlatformVersion::create([
            'version' => '1.1.0',
            'title' => 'Performance Improvements',
            'description' => 'Major performance improvements and bug fixes.',
            'is_active' => true,
            'released_at' => now()->addDays(30),
        ]);

        WhatsNew::create([
            'platform_version_id' => $version110->id,
            'title' => 'Enhanced Performance',
            'content' => 'We\'ve optimized our platform for faster loading times and better responsiveness.',
            'type' => 'improvement',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        WhatsNew::create([
            'platform_version_id' => $version110->id,
            'title' => 'Bug Fixes',
            'content' => 'Fixed several minor bugs to improve overall stability.',
            'type' => 'bugfix',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Create version 1.2.0
        $version120 = PlatformVersion::create([
            'version' => '1.2.0',
            'title' => 'New Features',
            'description' => 'Introducing exciting new features to enhance your workflow.',
            'is_active' => true,
            'released_at' => now()->addDays(60),
        ]);

        WhatsNew::create([
            'platform_version_id' => $version120->id,
            'title' => 'Advanced Analytics',
            'content' => 'New analytics dashboard with detailed insights and reporting capabilities.',
            'type' => 'feature',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        WhatsNew::create([
            'platform_version_id' => $version120->id,
            'title' => 'Security Enhancements',
            'content' => 'Enhanced security measures to protect your data and privacy.',
            'type' => 'security',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
} 