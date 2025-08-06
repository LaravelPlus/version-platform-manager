<?php

namespace LaravelPlus\VersionPlatformManager\Services;

use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Models\UserVersion;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Auth\Authenticatable;

class VersionService
{
    /**
     * Check if a user needs to see version updates.
     */
    public function userNeedsUpdate(Authenticatable $user): bool
    {
        $userVersion = $this->getUserVersion($user);
        $latestVersion = $this->getLatestPlatformVersion();

        if (!$latestVersion) {
            return false;
        }

        return $userVersion->isOlderThan($latestVersion->version);
    }

    /**
     * Get the user's current version.
     */
    public function getUserVersion(Authenticatable $user): UserVersion
    {
        return UserVersion::firstOrCreate(
            ['user_id' => $user->id],
            ['version' => config('version-platform-manager.default_user_version', '1.0.0')]
        );
    }

    /**
     * Update the user's version.
     */
    public function updateUserVersion(Authenticatable $user, string $version): void
    {
        $userVersion = $this->getUserVersion($user);
        $userVersion->update(['version' => $version]);
    }

    /**
     * Get the latest platform version.
     */
    public function getLatestPlatformVersion(): ?PlatformVersion
    {
        return PlatformVersion::active()
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Get all platform versions.
     */
    public function getPlatformVersions(): Collection
    {
        return PlatformVersion::active()
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get what's new content for a user.
     */
    public function getWhatsNewForUser(Authenticatable $user): Collection
    {
        $userVersion = $this->getUserVersion($user);
        $latestVersion = $this->getLatestPlatformVersion();

        if (!$latestVersion || !$userVersion->isOlderThan($latestVersion->version)) {
            return collect();
        }

        return $this->getWhatsNewBetweenVersions($userVersion->version, $latestVersion->version);
    }

    /**
     * Get what's new content between two versions.
     */
    public function getWhatsNewBetweenVersions(string $fromVersion, string $toVersion): Collection
    {
        return PlatformVersion::where('version', '>', $fromVersion)
            ->where('version', '<=', $toVersion)
            ->where('is_active', true)
            ->with(['activeWhatsNew'])
            ->get()
            ->flatMap(function ($version) {
                return $version->activeWhatsNew;
            });
    }

    /**
     * Mark a version as seen by the user.
     */
    public function markVersionAsSeen(Authenticatable $user, string $version): void
    {
        $userVersion = $this->getUserVersion($user);
        $userVersion->updateLastSeen($version);
    }

    /**
     * Create a new platform version.
     */
    public function createPlatformVersion(array $data): PlatformVersion
    {
        return PlatformVersion::create($data);
    }

    /**
     * Create new what's new content.
     */
    public function createWhatsNew(array $data): WhatsNew
    {
        return WhatsNew::create($data);
    }

    /**
     * Get users who need to see updates.
     */
    public function getUsersNeedingUpdates(): Collection
    {
        $latestVersion = $this->getLatestPlatformVersion();

        if (!$latestVersion) {
            return collect();
        }

        return UserVersion::olderThan($latestVersion->version)->get();
    }

    /**
     * Compare two versions.
     */
    public function compareVersions(string $version1, string $version2): int
    {
        return version_compare($version1, $version2);
    }

    /**
     * Check if a version is valid.
     */
    public function isValidVersion(string $version): bool
    {
        return preg_match('/^\d+\.\d+\.\d+$/', $version) === 1;
    }

    /**
     * Get version statistics.
     */
    public function getVersionStatistics(): array
    {
        $totalUsers = \App\Models\User::count();
        $userVersions = UserVersion::count();
        $latestVersion = $this->getLatestPlatformVersion();
        
        if (!$latestVersion) {
            return [
                'total_users' => $totalUsers,
                'users_with_versions' => $userVersions,
                'users_on_latest' => 0,
                'users_needing_update' => 0,
                'latest_version' => null,
                'total_versions' => PlatformVersion::count(),
                'active_versions' => PlatformVersion::where('is_active', true)->count(),
                'total_features' => WhatsNew::count(),
                'published_features' => WhatsNew::where('status', 'published')->count(),
                'adoption_rate' => 0,
            ];
        }

        $usersOnLatest = UserVersion::where('version', $latestVersion->version)->count();
        $usersNeedingUpdate = UserVersion::where('version', '<', $latestVersion->version)->count();
        $activeUsers = UserVersion::where('last_seen_at', '>=', now()->subDays(30))->count();
        
        // Calculate adoption rate based on users with version data, not total users
        $adoptionRate = $userVersions > 0 ? round(($usersOnLatest / $userVersions) * 100, 1) : 0;

        return [
            'total_users' => $totalUsers,
            'users_with_versions' => $userVersions,
            'users_on_latest' => $usersOnLatest,
            'users_needing_update' => $usersNeedingUpdate,
            'active_users' => $activeUsers,
            'latest_version' => $latestVersion->version,
            'total_versions' => PlatformVersion::count(),
            'active_versions' => PlatformVersion::where('is_active', true)->count(),
            'total_features' => WhatsNew::count(),
            'published_features' => WhatsNew::where('status', 'published')->count(),
            'adoption_rate' => $adoptionRate,
        ];
    }

    /**
     * Get what's new content for a specific version.
     */
    public function getWhatsNewForVersion(string $version): Collection
    {
        $platformVersion = PlatformVersion::where('version', $version)->first();
        
        if (!$platformVersion) {
            return collect();
        }

        return $platformVersion->activeWhatsNew;
    }

    /**
     * Get all what's new content.
     */
    public function getAllWhatsNew(): Collection
    {
        return WhatsNew::with('platformVersion')
            ->active()
            ->ordered()
            ->get();
    }
} 