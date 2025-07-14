<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default User Version
    |--------------------------------------------------------------------------
    |
    | The default version assigned to new users when they register.
    |
    */
    'default_user_version' => env('DEFAULT_USER_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Version Comparison
    |--------------------------------------------------------------------------
    |
    | How to compare versions. Options: 'semantic', 'numeric', 'string'
    |
    */
    'version_comparison' => env('VERSION_COMPARISON', 'semantic'),

    /*
    |--------------------------------------------------------------------------
    | Modal Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the what's new modal display.
    |
    */
    'modal' => [
        'auto_show' => env('VERSION_MODAL_AUTO_SHOW', true),
        'dismissible' => env('VERSION_MODAL_DISMISSIBLE', true),
        'show_once_per_session' => env('VERSION_MODAL_SHOW_ONCE_PER_SESSION', true),
        'delay' => env('VERSION_MODAL_DELAY', 1000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel
    |--------------------------------------------------------------------------
    |
    | Settings for the admin panel.
    |
    */
    'admin' => [
        'enabled' => env('VERSION_ADMIN_ENABLED', true),
        'route_prefix' => env('VERSION_ADMIN_ROUTE_PREFIX', 'admin/version-manager'),
        'route_name_prefix' => env('VERSION_ADMIN_ROUTE_NAME_PREFIX', 'version-manager'),
        'middleware' => ['web', 'auth'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Tables
    |--------------------------------------------------------------------------
    |
    | Table names for the package.
    |
    */
    'tables' => [
        'platform_versions' => 'platform_versions',
        'whats_new' => 'whats_new',
        'user_versions' => 'user_versions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Feature types for what's new content.
    |
    */
    'feature_types' => [
        'feature' => 'New Feature',
        'improvement' => 'Improvement',
        'bugfix' => 'Bug Fix',
        'security' => 'Security Update',
        'deprecation' => 'Deprecation',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Notification settings for version updates.
    |
    */
    'notifications' => [
        'enabled' => env('VERSION_NOTIFICATIONS_ENABLED', true),
        'channels' => ['mail', 'database'],
    ],
]; 