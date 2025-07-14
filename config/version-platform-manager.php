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
    | Admin Navbar Links
    |--------------------------------------------------------------------------
    |
    | Configure the links shown in the admin sidebar. You can add, remove, or
    | reorder links here. Each link should have a label, route (route name),
    | and SVG icon path (optional, for sidebar display).
    |
    */
    'navbar_links' => [
        [
            'label' => 'Dashboard',
            'route' => 'version-manager.dashboard',
            'icon' => '<svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" /></svg>'
        ],
        [
            'label' => 'Versions',
            'route' => 'version-manager.versions.index',
            'icon' => '<svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" /></svg>'
        ],
        [
            'label' => 'Users',
            'route' => 'version-manager.users.index',
            'icon' => '<svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>'
        ],
        [
            'label' => 'Analytics',
            'route' => 'version-manager.analytics.index',
            'icon' => '<svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>'
        ],
        [
            'label' => 'Logs',
            // You can use either 'route' or 'url' for custom links:
            // 'route' => 'admin.logs',
            'url' => '/admin/logs',
            'icon' => '<svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 9V7a5 5 0 0110 0v2m-1 4h-8" /></svg>',
            'target' => '_blank', // Optional: open in new tab
        ],
        // Example custom link:
        // [
        //     'label' => 'Logs',
        //     'route' => 'admin.logs',
        //     'icon' => '<svg ...></svg>'
        // ],
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

    'whats_new_signature' => env('VERSION_WHATS_NEW_SIGNATURE', 'Best regards, <br>Your Company Name'),
    'whats_new_exclude' => [
        'admin*',
        'api*',
        'login',
        'register',
        'password/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Public What's New Page
    |--------------------------------------------------------------------------
    |
    | Configuration for the standalone public 'What's New' page.
    |
    */
    'public_whats_new' => [
        'url' => env('VERSION_PUBLIC_WHATS_NEW_URL', 'whats-new'),
        'view' => env('VERSION_PUBLIC_WHATS_NEW_VIEW', 'version-platform-manager::whats-new.page'),
    ],
]; 