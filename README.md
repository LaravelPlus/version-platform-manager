# LaravelPlus Version Platform Manager

A Laravel package for managing platform versions and showing "what's new" content to users based on their current version.

## Features

- **Version Management**: Track platform versions and user versions
- **What's New Content**: Store and manage feature announcements
- **Smart Notifications**: Show modals to users based on their version
- **Version Comparison**: Compare user versions with platform versions
- **Admin Interface**: Manage versions and content through admin panel

## Installation

1. Install the package via Composer:

```bash
composer require laravelplus/version-platform-manager
```

2. Publish the configuration and migrations:

```bash
php artisan vendor:publish --provider="LaravelPlus\VersionPlatformManager\Providers\VersionPlatformManagerServiceProvider"
```

3. Run the migrations:

```bash
php artisan migrate
```

## Usage

### Basic Usage

The package automatically handles version checking and modal display. Simply include the component in your views:

```php
<x-what-is-new></x-what-is-new>
```

### Managing Platform Versions

Add a new platform version:

```php
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;

PlatformVersion::create([
    'version' => '1.0.0',
    'title' => 'Major Update',
    'description' => 'New features and improvements',
    'is_active' => true,
    'released_at' => now(),
]);
```

### Managing What's New Content

Add content for a specific version:

```php
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;

WhatsNew::create([
    'platform_version_id' => $version->id,
    'title' => 'New Feature',
    'content' => 'Description of the new feature',
    'type' => 'feature', // feature, improvement, bugfix, security
    'is_active' => true,
]);
```

### Checking User Versions

Check if a user needs to see updates:

```php
use LaravelPlus\VersionPlatformManager\Services\VersionService;

$versionService = app(VersionService::class);
$needsUpdate = $versionService->userNeedsUpdate($user);
```

## Configuration

The package configuration file `config/version-platform-manager.php` contains:

- Default user version
- Modal display settings
- Version comparison logic
- Admin panel settings

## Database Structure

### Platform Versions Table

Stores platform version information:

- `version`: Version string (e.g., "1.0.0")
- `title`: Version title
- `description`: Version description
- `is_active`: Whether the version is active
- `released_at`: Release date

### What's New Table

Stores feature announcements:

- `platform_version_id`: Reference to platform version
- `title`: Feature title
- `content`: Feature description
- `type`: Feature type (feature, improvement, bugfix, security)
- `is_active`: Whether the feature is active

### User Versions Table

Tracks user version information:

- `user_id`: User reference
- `version`: User's current version
- `last_seen_version`: Last version the user has seen
- `updated_at`: Last update timestamp

## Components

### What's New Modal

The package provides a Blade component that automatically shows the modal when needed:

```php
<x-what-is-new></x-what-is-new>
```

### Admin Components

Admin components for managing versions and content:

```php
<x-version-platform-manager::admin.versions></x-version-platform-manager::admin.versions>
<x-version-platform-manager::admin.whats-new></x-version-platform-manager::admin.whats-new>
```

## API

### VersionService

Main service for version management:

```php
$versionService = app(VersionService::class);

// Check if user needs update
$needsUpdate = $versionService->userNeedsUpdate($user);

// Get user's current version
$version = $versionService->getUserVersion($user);

// Update user version
$versionService->updateUserVersion($user, '1.0.0');

// Get what's new for user
$whatsNew = $versionService->getWhatsNewForUser($user);
```

## Events

The package fires several events:

- `UserVersionUpdated`: When a user's version is updated
- `PlatformVersionCreated`: When a new platform version is created
- `WhatsNewCreated`: When new content is added

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 