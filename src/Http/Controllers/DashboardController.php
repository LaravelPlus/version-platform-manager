<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

    /**
     * Check system health and return comprehensive status.
     */
    public function healthCheck(): JsonResponse
    {
        $healthStatus = [
            'timestamp' => now()->toISOString(),
            'overall_status' => 'healthy',
            'checks' => []
        ];

        // Check database connection
        try {
            DB::connection()->getPdo();
            $healthStatus['checks']['database_connection'] = [
                'status' => 'healthy',
                'message' => 'Database connection successful'
            ];
        } catch (\Exception $e) {
            $healthStatus['checks']['database_connection'] = [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
            $healthStatus['overall_status'] = 'unhealthy';
        }

        // Check required tables
        $requiredTables = [
            'platform_versions',
            'whats_new', 
            'user_versions',
            'users'
        ];

        foreach ($requiredTables as $table) {
            try {
                if (Schema::hasTable($table)) {
                    $recordCount = DB::table($table)->count();
                    $healthStatus['checks']['table_' . $table] = [
                        'status' => 'healthy',
                        'message' => "Table exists with {$recordCount} records",
                        'record_count' => $recordCount
                    ];
                } else {
                    $healthStatus['checks']['table_' . $table] = [
                        'status' => 'unhealthy',
                        'message' => "Table '{$table}' does not exist"
                    ];
                    $healthStatus['overall_status'] = 'unhealthy';
                }
            } catch (\Exception $e) {
                $healthStatus['checks']['table_' . $table] = [
                    'status' => 'unhealthy',
                    'message' => "Error checking table '{$table}': " . $e->getMessage()
                ];
                $healthStatus['overall_status'] = 'unhealthy';
            }
        }

        // Check storage permissions
        try {
            $storagePath = storage_path();
            if (is_writable($storagePath)) {
                $healthStatus['checks']['storage_permissions'] = [
                    'status' => 'healthy',
                    'message' => 'Storage directory is writable'
                ];
            } else {
                $healthStatus['checks']['storage_permissions'] = [
                    'status' => 'unhealthy',
                    'message' => 'Storage directory is not writable'
                ];
                $healthStatus['overall_status'] = 'unhealthy';
            }
        } catch (\Exception $e) {
            $healthStatus['checks']['storage_permissions'] = [
                'status' => 'unhealthy',
                'message' => 'Error checking storage permissions: ' . $e->getMessage()
            ];
            $healthStatus['overall_status'] = 'unhealthy';
        }

        // Check cache system
        try {
            $cacheKey = 'health_check_' . time();
            cache()->put($cacheKey, 'test', 60);
            $cached = cache()->get($cacheKey);
            cache()->forget($cacheKey);
            
            if ($cached === 'test') {
                $healthStatus['checks']['cache_system'] = [
                    'status' => 'healthy',
                    'message' => 'Cache system is working properly'
                ];
            } else {
                $healthStatus['checks']['cache_system'] = [
                    'status' => 'unhealthy',
                    'message' => 'Cache system is not working properly'
                ];
                $healthStatus['overall_status'] = 'unhealthy';
            }
        } catch (\Exception $e) {
            $healthStatus['checks']['cache_system'] = [
                'status' => 'unhealthy',
                'message' => 'Error checking cache system: ' . $e->getMessage()
            ];
            $healthStatus['overall_status'] = 'unhealthy';
        }

        // Check version manager statistics
        try {
            $versionCount = $this->platformVersionRepository->all()->count();
            $activeVersionCount = $this->platformVersionRepository->active()->count();
            $userCount = $this->userVersionRepository->all()->count();
            
            $healthStatus['checks']['version_manager_stats'] = [
                'status' => 'healthy',
                'message' => "Version manager operational",
                'data' => [
                    'total_versions' => $versionCount,
                    'active_versions' => $activeVersionCount,
                    'total_users' => $userCount
                ]
            ];
        } catch (\Exception $e) {
            $healthStatus['checks']['version_manager_stats'] = [
                'status' => 'unhealthy',
                'message' => 'Error checking version manager stats: ' . $e->getMessage()
            ];
            $healthStatus['overall_status'] = 'unhealthy';
        }

        return response()->json($healthStatus);
    }
} 