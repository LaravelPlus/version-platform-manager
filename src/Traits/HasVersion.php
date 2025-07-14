<?php

namespace LaravelPlus\VersionPlatformManager\Traits;

use LaravelPlus\VersionPlatformManager\Models\UserVersion;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

trait HasVersion
{
    /**
     * Get the user's version record.
     */
    public function userVersion()
    {
        return $this->hasOne(UserVersion::class);
    }

    /**
     * Get the user's current version.
     */
    public function getCurrentVersion(): string
    {
        return $this->userVersion?->version ?? config('version-platform-manager.default_user_version', '1.0.0');
    }

    /**
     * Update the user's version.
     */
    public function updateVersion(string $version): void
    {
        $versionService = app(VersionService::class);
        $versionService->updateUserVersion($this, $version);
    }

    /**
     * Check if the user needs to see version updates.
     */
    public function needsVersionUpdate(): bool
    {
        $versionService = app(VersionService::class);
        return $versionService->userNeedsUpdate($this);
    }

    /**
     * Get what's new content for the user.
     */
    public function getWhatsNew()
    {
        $versionService = app(VersionService::class);
        return $versionService->getWhatsNewForUser($this);
    }

    /**
     * Mark a version as seen by the user.
     */
    public function markVersionAsSeen(string $version): void
    {
        $versionService = app(VersionService::class);
        $versionService->markVersionAsSeen($this, $version);
    }

    /**
     * Check if the user's version is older than the given version.
     */
    public function isVersionOlderThan(string $version): bool
    {
        $currentVersion = $this->getCurrentVersion();
        return version_compare($currentVersion, $version, '<');
    }

    /**
     * Check if the user's version is newer than the given version.
     */
    public function isVersionNewerThan(string $version): bool
    {
        $currentVersion = $this->getCurrentVersion();
        return version_compare($currentVersion, $version, '>');
    }

    /**
     * Check if the user's version matches the given version.
     */
    public function isVersionEqualTo(string $version): bool
    {
        $currentVersion = $this->getCurrentVersion();
        return version_compare($currentVersion, $version, '=');
    }
} 