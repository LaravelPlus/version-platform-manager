# Installation Guide

## Step 1: Add the Package to Your Project

Add the package to your `composer.json`:

```json
{
    "require": {
        "laravelplus/version-platform-manager": "dev-master"
    },
    "repositories": [
        {
            "type": "path",
            "url": "./packages/laravelplus/version-platform-manager"
        }
    ]
}
```

## Step 2: Install Dependencies

```bash
composer install
```

## Step 3: Publish the Package

```bash
php artisan vendor:publish --provider="LaravelPlus\VersionPlatformManager\Providers\VersionPlatformManagerServiceProvider"
```

## Step 4: Run Migrations

```bash
php artisan migrate
```

## Step 5: Add the Trait to Your User Model

Add the `HasVersion` trait to your `User` model:

```php
<?php

namespace App\Models;

use LaravelPlus\VersionPlatformManager\Traits\HasVersion;

class User extends Authenticatable
{
    use HasVersion;
    
    // ... rest of your model
}
```

## Step 6: Replace the Existing Component

Replace your existing `what-is-new.blade.php` component with the package component:

```php
// In your home.blade.php or any view
<x-version-platform-manager::whats-new></x-version-platform-manager::whats-new>
```

## Step 7: Create Initial Data (Optional)

Run the seeder to create sample data:

```bash
php artisan db:seed --class="LaravelPlus\VersionPlatformManager\Database\Seeders\VersionPlatformManagerSeeder"
```

## Step 8: Test the Package

Create a platform version:

```bash
php artisan version-platform:create-version "1.0.0" "Initial Release" --description="Welcome to our platform!"
```

Create what's new content:

```bash
php artisan version-platform:create-whats-new "1.0.0" "New Feature" "This is a new feature description" --type=feature
```

## Usage Examples

### In Your Views

```php
// Show what's new modal (automatically checks user version)
<x-version-platform-manager::whats-new></x-version-platform-manager::whats-new>
```

### In Your Controllers

```php
use LaravelPlus\VersionPlatformManager\Services\VersionService;

class HomeController extends Controller
{
    public function index(VersionService $versionService)
    {
        $user = auth()->user();
        
        // Check if user needs updates
        if ($versionService->userNeedsUpdate($user)) {
            $whatsNew = $versionService->getWhatsNewForUser($user);
            // Handle updates...
        }
        
        return view('home');
    }
}
```

### Using the User Model

```php
$user = auth()->user();

// Check if user needs updates
if ($user->needsVersionUpdate()) {
    $whatsNew = $user->getWhatsNew();
    // Handle updates...
}

// Update user version
$user->updateVersion('1.1.0');
```

## Configuration

The package configuration is published to `config/version-platform-manager.php`. You can customize:

- Default user version
- Version comparison method
- Modal settings
- Admin panel settings
- Feature types

## Admin Panel

Access the admin panel at `/admin/versions` to manage:

- Platform versions
- What's new content
- User version statistics

## Console Commands

- `php artisan version-platform:create-version` - Create a new platform version
- `php artisan version-platform:create-whats-new` - Create new what's new content

## Troubleshooting

### Common Issues

1. **Component not showing**: Make sure the user is authenticated and has a version older than the latest platform version.

2. **Migration errors**: Ensure all migrations are run and the database tables exist.

3. **Trait not working**: Make sure the `HasVersion` trait is added to your `User` model.

### Support

For issues and questions, please check the package documentation or create an issue in the repository. 