<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Models\UserVersion;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and recent activity.
     */
    public function index(): View
    {
        $statistics = [
            'total_versions' => PlatformVersion::count(),
            'active_versions' => PlatformVersion::where('is_active', true)->count(),
            'total_users' => User::count(),
            'users_with_versions' => UserVersion::count(),
            'versions_this_month' => PlatformVersion::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        $recentVersions = PlatformVersion::orderBy('created_at', 'desc')
            ->with('whatsNew')
            ->take(5)
            ->get();

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
            $versionCount = PlatformVersion::count();
            $activeVersionCount = PlatformVersion::where('is_active', true)->count();
            $userCount = User::count();
            
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