<?php

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

class DashboardController extends Controller
{
    public function __construct(
        private VersionService $versionService
    ) {}

    /**
     * Display the dashboard with statistics and recent activity.
     */
    public function index()
    {
        // Get statistics
        $statistics = [
            'total_versions' => PlatformVersion::count(),
            'active_versions' => PlatformVersion::where('is_active', true)->count(),
            'total_users' => 0, // This would be from your user model
            'versions_this_month' => PlatformVersion::whereMonth('created_at', now()->month)->count(),
        ];

        // Get recent versions
        $recentVersions = PlatformVersion::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('version-platform-manager::admin.dashboard', compact('statistics', 'recentVersions'));
    }
} 