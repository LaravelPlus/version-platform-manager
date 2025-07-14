# Version Platform Manager Configuration

This document explains how to configure the Version Platform Manager package to suit your needs.

## Route Configuration

The package allows you to customize the route prefixes and naming conventions through environment variables or direct configuration.

### Environment Variables

Add these to your `.env` file:

```env
# Route prefix for admin panel (default: admin/version-manager)
VERSION_ADMIN_ROUTE_PREFIX=admin/version-manager

# Route name prefix (default: version-manager)
VERSION_ADMIN_ROUTE_NAME_PREFIX=version-manager

# Enable/disable admin panel (default: true)
VERSION_ADMIN_ENABLED=true
```

### Configuration File

You can also modify the configuration directly in `config/version-platform-manager.php`:

```php
'admin' => [
    'enabled' => env('VERSION_ADMIN_ENABLED', true),
    'route_prefix' => env('VERSION_ADMIN_ROUTE_PREFIX', 'admin/version-manager'),
    'route_name_prefix' => env('VERSION_ADMIN_ROUTE_NAME_PREFIX', 'version-manager'),
    'middleware' => ['web', 'auth'],
],
```

## Route Examples

With default configuration, your routes will be:

- **Dashboard**: `http://your-app.com/admin/version-manager/`
- **Versions**: `http://your-app.com/admin/version-manager/versions`
- **Users**: `http://your-app.com/admin/version-manager/users`
- **Analytics**: `http://your-app.com/admin/version-manager/analytics`

Route names will be:
- `version-manager.dashboard`
- `version-manager.versions.index`
- `version-manager.users.index`
- `version-manager.analytics.index`

## Custom Route Examples

If you want to change the routes to something like `admin/versions`, you can set:

```env
VERSION_ADMIN_ROUTE_PREFIX=admin/versions
VERSION_ADMIN_ROUTE_NAME_PREFIX=versions
```

This would give you:
- **Dashboard**: `http://your-app.com/admin/versions/`
- **Versions**: `http://your-app.com/admin/versions/versions`
- Route names: `versions.dashboard`, `versions.versions.index`, etc.

## Using Route Helper

The package provides a helper class to generate route names consistently:

```php
use LaravelPlus\VersionPlatformManager\Helpers\RouteHelper;

// Get route names
RouteHelper::dashboard(); // returns 'version-manager.dashboard'
RouteHelper::versions('create'); // returns 'version-manager.versions.create'
RouteHelper::users('index'); // returns 'version-manager.users.index'
RouteHelper::analytics('export'); // returns 'version-manager.analytics.export'
```

## Other Configuration Options

### Default User Version
```env
DEFAULT_USER_VERSION=1.0.0
```

### Version Comparison Method
```env
VERSION_COMPARISON=semantic
```
Options: `semantic`, `numeric`, `string`

### Modal Settings
```env
VERSION_MODAL_AUTO_SHOW=true
VERSION_MODAL_DISMISSIBLE=true
VERSION_MODAL_SHOW_ONCE_PER_SESSION=true
VERSION_MODAL_DELAY=1000
```

### Notifications
```env
VERSION_NOTIFICATIONS_ENABLED=true
```

## Publishing Configuration

To publish the configuration file:

```bash
php artisan vendor:publish --tag=version-platform-manager-config
```

This will create `config/version-platform-manager.php` in your application.

## Middleware Configuration

You can customize the middleware applied to admin routes:

```php
'admin' => [
    'middleware' => ['web', 'auth', 'admin'], // Add your custom middleware
],
```

## Database Tables

You can customize table names if needed:

```php
'tables' => [
    'platform_versions' => 'platform_versions',
    'whats_new' => 'whats_new',
    'user_versions' => 'user_versions',
],
```

## Feature Types

Customize the feature types for "What's New" content:

```php
'feature_types' => [
    'feature' => 'New Feature',
    'improvement' => 'Improvement',
    'bugfix' => 'Bug Fix',
    'security' => 'Security Update',
    'deprecation' => 'Deprecation',
],
``` 