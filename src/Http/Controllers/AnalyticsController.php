<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use LaravelPlus\VersionPlatformManager\Services\AnalyticsService;

class AnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Display the analytics dashboard.
     */
    public function index(): View
    {
        $analyticsData = $this->analyticsService->getAnalyticsData();
        
        return view('version-platform-manager::admin.analytics.index', [
            'analyticsData' => $analyticsData,
        ]);
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request): Response
    {
        $data = $this->analyticsService->getAnalyticsData();
        
        $filename = 'analytics_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
} 