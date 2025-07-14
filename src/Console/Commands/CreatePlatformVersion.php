<?php

namespace LaravelPlus\VersionPlatformManager\Console\Commands;

use Illuminate\Console\Command;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

class CreatePlatformVersion extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'version-platform:create-version 
                            {version : The version number (e.g., 1.0.0)}
                            {title : The version title}
                            {--description= : The version description}
                            {--active : Whether the version is active}
                            {--released-at= : The release date}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new platform version';

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
        $description = $this->option('description');
        $isActive = $this->option('active');
        $releasedAt = $this->option('released-at');

        if (!$this->versionService->isValidVersion($version)) {
            $this->error('Invalid version format. Please use semantic versioning (e.g., 1.0.0)');
            return 1;
        }

        try {
            $this->versionService->createPlatformVersion([
                'version' => $version,
                'title' => $title,
                'description' => $description,
                'is_active' => $isActive,
                'released_at' => $releasedAt ? now()->parse($releasedAt) : now(),
            ]);

            $this->info("Platform version {$version} created successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to create platform version: {$e->getMessage()}");
            return 1;
        }
    }
} 