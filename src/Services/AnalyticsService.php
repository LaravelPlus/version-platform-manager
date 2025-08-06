<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Services;

use LaravelPlus\VersionPlatformManager\Contracts\UserRepositoryInterface;
use LaravelPlus\VersionPlatformManager\Contracts\UserVersionRepositoryInterface;
use LaravelPlus\VersionPlatformManager\Contracts\PlatformVersionRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserVersionRepositoryInterface $userVersionRepository,
        private PlatformVersionRepositoryInterface $platformVersionRepository
    ) {}

    /**
     * Get analytics data for the dashboard.
     */
    public function getAnalyticsData(): array
    {
        return [
            'metrics' => $this->getMetrics(),
            'versionAdoption' => $this->getVersionAdoption(),
            'userActivity' => $this->getUserActivity(),
            'updateNotifications' => $this->getUpdateNotifications(),
            'topUsers' => $this->getTopUsers(),
            'recentActivity' => $this->getRecentActivity(),
        ];
    }

    /**
     * Get key metrics.
     */
    private function getMetrics(): array
    {
        $totalUsers = \App\Models\User::count();
        $userVersions = \LaravelPlus\VersionPlatformManager\Models\UserVersion::all();
        $activeUsers = $userVersions->where('last_seen_at', '>=', now()->subDays(30))->count();
        
        // Calculate adoption rate (users with latest version)
        $latestVersion = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::orderBy('version', 'desc')->first();
        $adoptionRate = $latestVersion ? 
            ($userVersions->where('version', $latestVersion->version)->count() / max($totalUsers, 1)) * 100 : 0;
        
        // Calculate average update time (days since last version update)
        $avgUpdateTime = $userVersions->whereNotNull('last_seen_at')->avg(
            fn($uv) => $uv->last_seen_at ? now()->diffInDays($uv->last_seen_at) : 0
        ) ?: 0;

        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'adoptionRate' => round($adoptionRate, 1),
            'avgUpdateTime' => round($avgUpdateTime, 1),
        ];
    }

    /**
     * Get version adoption data.
     */
    private function getVersionAdoption(): array
    {
        $userVersions = \LaravelPlus\VersionPlatformManager\Models\UserVersion::all();
        $totalUsers = $userVersions->count();
        
        if ($totalUsers === 0) {
            return [];
        }

        $versionCounts = $userVersions->groupBy('version')
            ->map(fn($group) => $group->count())
            ->sortByDesc(function ($count, $version) {
                return version_compare($version, '0.0.0', '>') ? $version : '0.0.0';
            });

        return $versionCounts->map(function ($count, $version) use ($totalUsers) {
            return [
                'version' => $version,
                'users' => $count,
                'percentage' => round(($count / $totalUsers) * 100, 1),
            ];
        })->values()->toArray();
    }

    /**
     * Get user activity data.
     */
    private function getUserActivity(): array
    {
        $userVersions = \LaravelPlus\VersionPlatformManager\Models\UserVersion::all();
        $totalUsers = $userVersions->count();
        
        if ($totalUsers === 0) {
            return [];
        }

        $today = $userVersions->where('last_seen_at', '>=', now()->startOfDay())->count();
        $thisWeek = $userVersions->where('last_seen_at', '>=', now()->startOfWeek())->count();
        $thisMonth = $userVersions->where('last_seen_at', '>=', now()->startOfMonth())->count();

        return [
            [
                'period' => 'Today',
                'users' => $today,
                'percentage' => round(($today / $totalUsers) * 100, 1),
            ],
            [
                'period' => 'This Week',
                'users' => $thisWeek,
                'percentage' => round(($thisWeek / $totalUsers) * 100, 1),
            ],
            [
                'period' => 'This Month',
                'users' => $thisMonth,
                'percentage' => round(($thisMonth / $totalUsers) * 100, 1),
            ],
        ];
    }

    /**
     * Get update notifications data.
     */
    private function getUpdateNotifications(): array
    {
        $platformVersions = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::where('is_active', true)
            ->orderBy('released_at', 'desc')
            ->take(5)
            ->get();

        return $platformVersions->map(function ($version, $index) {
            return [
                'id' => $index + 1,
                'version' => $version->version,
                'date' => $version->released_at?->format('Y-m-d') ?? 'N/A',
                'sent' => \LaravelPlus\VersionPlatformManager\Models\UserVersion::where('version', $version->version)->count(),
            ];
        })->toArray();
    }

    /**
     * Get top users data.
     */
    private function getTopUsers(): array
    {
        $userVersions = \LaravelPlus\VersionPlatformManager\Models\UserVersion::with('user')
            ->whereNotNull('last_seen_at')
            ->orderBy('last_seen_at', 'desc')
            ->take(5)
            ->get();

        return $userVersions->map(function ($userVersion, $index) {
            $user = $userVersion->user;
            return [
                'id' => $index + 1,
                'name' => $user?->name ?? 'Unknown User',
                'version' => $userVersion->version,
                'loginCount' => $userVersion->metadata['login_count'] ?? 0,
            ];
        })->toArray();
    }

    /**
     * Get recent activity data.
     */
    private function getRecentActivity(): array
    {
        $activities = collect();
        
        // Add version releases
        $recentVersions = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::where('is_active', true)
            ->orderBy('released_at', 'desc')
            ->take(3)
            ->get();
            
        foreach ($recentVersions as $version) {
            $activities->push([
                'id' => 'v' . $version->id,
                'type' => 'update',
                'title' => "Version {$version->version} released",
                'time' => $version->released_at?->diffForHumans() ?? 'Unknown',
            ]);
        }

        // Add recent user version updates
        $recentUserVersions = \LaravelPlus\VersionPlatformManager\Models\UserVersion::whereNotNull('last_seen_at')
            ->orderBy('last_seen_at', 'desc')
            ->take(2)
            ->get();
            
        foreach ($recentUserVersions as $userVersion) {
            $activities->push([
                'id' => 'uv' . $userVersion->id,
                'type' => 'user',
                'title' => "User updated to {$userVersion->version}",
                'time' => $userVersion->last_seen_at->diffForHumans(),
            ]);
        }

        return $activities->sortByDesc(function ($activity) {
            return str_contains($activity['id'], 'v') ? 1 : 0;
        })->take(5)->toArray();
    }
} 