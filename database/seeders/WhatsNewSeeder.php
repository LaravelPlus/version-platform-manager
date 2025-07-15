<?php

namespace LaravelPlus\VersionPlatformManager\Database\Seeders;

use Illuminate\Database\Seeder;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;

class WhatsNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $version = PlatformVersion::first();
        
        if (!$version) {
            $this->command->info('No platform version found. Please create a platform version first.');
            return;
        }

        $sampleData = [
            [
                'title' => 'New Dashboard Interface',
                'content' => 'We have completely redesigned the dashboard with a modern, intuitive interface that makes navigation easier than ever.',
                'type' => 'feature',
                'sort_order' => 1,
            ],
            [
                'title' => 'Performance Improvements',
                'content' => 'Significant performance improvements across the application, resulting in faster page loads and better user experience.',
                'type' => 'improvement',
                'sort_order' => 2,
            ],
            [
                'title' => 'Security Enhancements',
                'content' => 'Enhanced security measures including improved authentication, better password policies, and additional data protection features.',
                'type' => 'security',
                'sort_order' => 3,
            ],
            [
                'title' => 'Bug Fixes',
                'content' => 'Fixed several minor bugs including navigation issues, form validation problems, and display inconsistencies.',
                'type' => 'bugfix',
                'sort_order' => 4,
            ],
        ];

        foreach ($sampleData as $data) {
            WhatsNew::create([
                'platform_version_id' => $version->id,
                'title' => $data['title'],
                'content' => $data['content'],
                'type' => $data['type'],
                'is_active' => true,
                'sort_order' => $data['sort_order'],
            ]);
        }

        $this->command->info('Sample WhatsNew content created successfully!');
    }
} 