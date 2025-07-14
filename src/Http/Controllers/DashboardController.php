<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\View\View;
use LaravelPlus\VersionPlatformManager\Contracts\PlatformVersionRepositoryInterface;
use LaravelPlus\VersionPlatformManager\Contracts\UserVersionRepositoryInterface;

class DashboardController extends Controller
{
    public function __construct(
        private PlatformVersionRepositoryInterface $platformVersionRepository,
        private UserVersionRepositoryInterface $userVersionRepository
    ) {}

    /**
     * Display the dashboard with statistics and recent activity.
     */
    public function index(): View
    {
        $statistics = [
            'total_versions' => $this->platformVersionRepository->all()->count(),
            'active_versions' => $this->platformVersionRepository->active()->count(),
            'total_users' => $this->userVersionRepository->all()->count(),
            'versions_this_month' => $this->platformVersionRepository->all()->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        $recentVersions = $this->platformVersionRepository->all()->sortByDesc('created_at')->take(5);

        return view('version-platform-manager::admin.dashboard', compact('statistics', 'recentVersions'));
    }
} 