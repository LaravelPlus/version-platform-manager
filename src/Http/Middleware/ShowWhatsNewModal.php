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
            $userNeedsUpdate = $versionService->userNeedsUpdate(Auth::user());
            $whatsNewUrl = '/' . ltrim(config('version-platform-manager.public_whats_new.url', 'whats-new'), '/');
            $onWhatsNewPage = $request->path() === ltrim($whatsNewUrl, '/');

            if ($userNeedsUpdate) {
                // Avoid redirect loop
                if (!$onWhatsNewPage) {
                    return redirect($whatsNewUrl);
                }
            } else {
                // If already read and on whats-new page, redirect to home
                if ($onWhatsNewPage) {
                    return redirect()->route('home');
                }
            }
        }

        return $next($request);
    }
} 