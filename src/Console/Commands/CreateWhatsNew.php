<?php

namespace LaravelPlus\VersionPlatformManager\Console\Commands;

use Illuminate\Console\Command;
use LaravelPlus\VersionPlatformManager\Services\VersionService;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;

class CreateWhatsNew extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'version-platform:create-whats-new 
                            {version : The platform version (e.g., 1.0.0)}
                            {title : The feature title}
                            {content : The feature content}
                            {--type=feature : The feature type (feature, improvement, bugfix, security, deprecation)}
                            {--active : Whether the feature is active}
                            {--sort-order=0 : The sort order}';

    /**
     * The console command description.
     */
    protected $description = 'Create new what\'s new content for a platform version';

    public function __construct(
        private VersionService $versionService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $version = $this->argument('version');
        $title = $this->argument('title');
        $content = $this->argument('content');
        $type = $this->option('type');
        $isActive = $this->option('active');
        $sortOrder = $this->option('sort-order');

        // Find the platform version
        $platformVersion = PlatformVersion::where('version', $version)->first();

        if (!$platformVersion) {
            $this->error("Platform version {$version} not found. Please create it first.");
            return 1;
        }

        $validTypes = ['feature', 'improvement', 'bugfix', 'security', 'deprecation'];
        if (!in_array($type, $validTypes)) {
            $this->error("Invalid type. Must be one of: " . implode(', ', $validTypes));
            return 1;
        }

        try {
            $this->versionService->createWhatsNew([
                'platform_version_id' => $platformVersion->id,
                'title' => $title,
                'content' => $content,
                'type' => $type,
                'is_active' => $isActive,
                'sort_order' => $sortOrder,
            ]);

            $this->info("What's new content created successfully for version {$version}!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to create what's new content: {$e->getMessage()}");
            return 1;
        }
    }
} 