<?php

namespace LaravelPlus\VersionPlatformManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

class ShowWhatsNewModal
{
    public function handle(Request $request, Closure $next)
    {
        $excluded = config('version-platform-manager.whats_new_exclude', [
            'admin*',
            'api*',
            'login',
            'register',
            'password/*',
        ]);

        $shouldCheck = $request->isMethod('get') && !collect($excluded)->contains(function ($pattern) use ($request) {
            return $request->is($pattern);
        });

        if ($shouldCheck && Auth::check()) {
            $versionService = app(VersionService::class);
            if ($versionService->userNeedsUpdate(Auth::user())) {
                // Avoid redirect loop
                $whatsNewUrl = '/' . ltrim(config('version-platform-manager.public_whats_new.url', 'whats-new'), '/');
                if ($request->path() !== ltrim($whatsNewUrl, '/')) {
                    return redirect($whatsNewUrl);
                }
            }
        }

        return $next($request);
    }
} 