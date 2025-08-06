<?php

namespace LaravelPlus\VersionPlatformManager\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaravelPlus\VersionPlatformManager\Services\VersionService;
use Symfony\Component\HttpFoundation\Response;

class CheckVersionUpdates
{
    public function __construct(
        private VersionService $versionService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for excluded routes
        $excludedRoutes = config('version-platform-manager.whats_new_exclude', []);
        foreach ($excludedRoutes as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Only check for authenticated users
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        
        // Check if user needs to see updates
        if ($this->versionService->userNeedsUpdate($user)) {
            $latestVersion = $this->versionService->getLatestPlatformVersion();
            
            if ($latestVersion) {
                // Get what's new content for the user
                $whatsNew = $this->versionService->getWhatsNewForUser($user);
                
                // Share data with the view
                view()->share('version_update_data', [
                    'version' => $latestVersion->version,
                    'title' => $latestVersion->title,
                    'description' => $latestVersion->description,
                    'whats_new' => $whatsNew,
                    'markdown_content' => $latestVersion->getWhatsNewMarkdownAttribute(),
                ]);
            }
        }

        return $next($request);
    }
} 