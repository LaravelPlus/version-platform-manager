# Version Platform Manager - Modal Usage

This document explains how to use the new version update modal functionality.

## Overview

The version platform manager now includes an automatic modal system that shows users what's new when they log in or visit pages after a version update.

## Features

1. **Automatic Detection**: Middleware checks if user needs to see version updates
2. **Modal Display**: Beautiful modal popup with version content
3. **Cookie Tracking**: 1-day cookie prevents showing same version again
4. **Read/Skip Options**: Users can mark as read or skip for now
5. **Markdown Support**: Rich content support with markdown rendering

## Setup

### 1. Register Middleware

Add the middleware to your routes where you want the modal to appear:

```php
Route::middleware(['web', 'auth', 'version.updates'])->group(function () {
    // Your routes here
});
```

### 2. Include Modal Component

Add the modal component to your layout:

```blade
<!-- In your main layout -->
<x-version-platform-manager::version-update-modal />
```

### 3. Create a Version

1. Go to `/admin/version-manager/versions/create`
2. Fill in version details
3. Add markdown content in the "What's New" section
4. Save the version

## How It Works

### Middleware Check

The `CheckVersionUpdates` middleware:
- Checks if user is authenticated
- Compares user's version with latest platform version
- Sets up modal data if update is needed
- Excludes certain routes (configurable)

### Modal Display

The modal appears automatically when:
- User has an older version than latest
- User hasn't read/skipped this version (cookie check)
- User is on a non-excluded route

### User Actions

Users can:
- **Mark as Read**: Updates their version and sets a 1-day cookie
- **Skip**: Sets a 1-day cookie without updating version
- **Close**: Just closes the modal (will show again next time)

## Configuration

### Excluded Routes

Configure routes to exclude from version checks in `config/version-platform-manager.php`:

```php
'whats_new_exclude' => [
    'admin*',
    'api*',
    'login',
    'register',
    'password/*',
],
```

### Modal Settings

Configure modal behavior:

```php
'modal' => [
    'auto_show' => env('VERSION_MODAL_AUTO_SHOW', true),
    'dismissible' => env('VERSION_MODAL_DISMISSIBLE', true),
    'show_once_per_session' => env('VERSION_MODAL_SHOW_ONCE_PER_SESSION', true),
    'delay' => env('VERSION_MODAL_DELAY', 1000), // milliseconds
],
```

## API Endpoints

### Check if should show update
```
GET /version-platform-manager/should-show-update
```

### Mark as read
```
POST /version-platform-manager/mark-as-read
{
    "version": "1.1.0"
}
```

### Skip version
```
POST /version-platform-manager/skip
{
    "version": "1.1.0"
}
```

## Testing

Visit `/version-example` to test the modal functionality.

## Content Format

### Markdown Content

Use markdown in the version creation form:

```markdown
# What's New in Version 1.1.0

## 🎉 New Features
- Added dark mode support
- New dashboard widgets

## ⚡ Improvements
- Faster page loading
- Better mobile experience

## 🐛 Bug Fixes
- Fixed login issue
- Resolved data export problem
```

### Emoji Categories

- 🎉 Feature
- ⚡ Improvement  
- 🐛 Bug Fix
- 🔒 Security
- ⚠️ Deprecation

## Database Schema

The system uses these tables:
- `platform_versions`: Version information
- `user_versions`: User version tracking
- `whats_new`: Version content items

## Cookie Management

Cookies are set for 1 day (86400 seconds):
- `version_{version}_read`: When user marks as read
- `version_{version}_skipped`: When user skips

## Troubleshooting

### Modal not showing
1. Check if user is authenticated
2. Verify user has older version than latest
3. Check if cookies are blocking display
4. Ensure middleware is applied to route

### Content not displaying
1. Check if markdown content is saved in version metadata
2. Verify Vue.js is loaded
3. Check browser console for JavaScript errors

### Styling issues
1. Ensure Tailwind CSS is loaded
2. Check if custom CSS conflicts with modal styles
3. Verify responsive design classes 