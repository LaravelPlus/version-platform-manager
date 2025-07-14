<?php

namespace LaravelPlus\VersionPlatformManager\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelPlus\VersionPlatformManager\Services\VersionService;
use LaravelPlus\VersionPlatformManager\Console\Commands\CreatePlatformVersion;
use LaravelPlus\VersionPlatformManager\Console\Commands\CreateWhatsNew;

class VersionPlatformManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/version-platform-manager.php', 'version-platform-manager'
        );

        $this->app->singleton(VersionService::class, function ($app) {
            return new VersionService();
        });

        $this->app->bind(\LaravelPlus\VersionPlatformManager\Contracts\PlatformVersionRepositoryInterface::class, \LaravelPlus\VersionPlatformManager\Repositories\PlatformVersionRepository::class);
        $this->app->bind(\LaravelPlus\VersionPlatformManager\Contracts\WhatsNewRepositoryInterface::class, \LaravelPlus\VersionPlatformManager\Repositories\WhatsNewRepository::class);
        $this->app->bind(\LaravelPlus\VersionPlatformManager\Contracts\UserVersionRepositoryInterface::class, \LaravelPlus\VersionPlatformManager\Repositories\UserVersionRepository::class);
        $this->app->bind(\LaravelPlus\VersionPlatformManager\Contracts\UserRepositoryInterface::class, \LaravelPlus\VersionPlatformManager\Repositories\UserRepository::class);
        
        // Register AnalyticsService
        $this->app->singleton(\LaravelPlus\VersionPlatformManager\Services\AnalyticsService::class, function ($app) {
            return new \LaravelPlus\VersionPlatformManager\Services\AnalyticsService(
                $app->make(\LaravelPlus\VersionPlatformManager\Contracts\UserRepositoryInterface::class),
                $app->make(\LaravelPlus\VersionPlatformManager\Contracts\UserVersionRepositoryInterface::class),
                $app->make(\LaravelPlus\VersionPlatformManager\Contracts\PlatformVersionRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../../config/version-platform-manager.php' => config_path('version-platform-manager.php'),
        ], 'version-platform-manager-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
        ], 'version-platform-manager-migrations');

        // Publish views
        $this->publishes([
            __DIR__.'/../../resources/views/' => resource_path('views/vendor/version-platform-manager'),
        ], 'version-platform-manager-views');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'version-platform-manager');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePlatformVersion::class,
                CreateWhatsNew::class,
            ]);
        }

        // Register Blade components
        $this->registerBladeComponents();
    }

    /**
     * Register Blade components.
     */
    protected function registerBladeComponents(): void
    {
        $this->loadViewComponentsAs('version-platform-manager', [
            \LaravelPlus\VersionPlatformManager\View\Components\WhatsNew::class,
        ]);
    }
} 