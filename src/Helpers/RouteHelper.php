<?php

namespace LaravelPlus\VersionPlatformManager\Helpers;

class RouteHelper
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
        return static::getPrefix() . '.' . $name;
    }

    /**
     * Get dashboard route name
     */
    public static function dashboard(): string
    {
        return static::route('dashboard');
    }

    /**
     * Get versions route names
     */
    public static function versions(string $action = 'index'): string
    {
        return static::route('versions.' . $action);
    }

    /**
     * Get users route names
     */
    public static function users(string $action = 'index'): string
    {
        return static::route('users.' . $action);
    }

    /**
     * Get analytics route names
     */
    public static function analytics(string $action = 'index'): string
    {
        return static::route('analytics.' . $action);
    }

    /**
     * Get whats-new route names
     */
    public static function whatsNew(string $action = 'index'): string
    {
        return static::route('whats-new.' . $action);
    }
} 