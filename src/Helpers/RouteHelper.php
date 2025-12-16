<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Helpers;

final class RouteHelper
{
    /**
     * Get the route name prefix from config
     */
    public static function getPrefix(): string
    {
        return config('version-platform-manager.admin.route_name_prefix', 'version-manager');
    }

    /**
     * Generate a route name with the configured prefix
     */
    public static function route(string $name): string
    {
        return self::getPrefix() . '.' . $name;
    }

    /**
     * Get dashboard route name
     */
    public static function dashboard(): string
    {
        return self::route('dashboard');
    }

    /**
     * Get versions route names
     */
    public static function versions(string $action = 'index'): string
    {
        return self::route('versions.' . $action);
    }

    /**
     * Get users route names
     */
    public static function users(string $action = 'index'): string
    {
        return self::route('users.' . $action);
    }

    /**
     * Get analytics route names
     */
    public static function analytics(string $action = 'index'): string
    {
        return self::route('analytics.' . $action);
    }

    /**
     * Get whats-new route names
     */
    public static function whatsNew(string $action = 'index'): string
    {
        return self::route('whats-new.' . $action);
    }
}
