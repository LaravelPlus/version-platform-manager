<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

final class ShowWhatsNewModal
{
    public function handle(Request $request, Closure $next)
    {
        $excluded = config('version-platform-manager.whats_new_exclude', [
            'admin*',
            'api*',
            'login',
            'whats-new',
            'register',
            'password/*',
        ]);

        $shouldCheck = $request->isMethod('get') && !collect($excluded)->contains(fn ($pattern) => $request->is($pattern));

        if ($shouldCheck && Auth::check()) {
            $versionService = app(VersionService::class);
            $userNeedsUpdate = $versionService->userNeedsUpdate(Auth::user());
            $whatsNewUrl = '/' . mb_ltrim(config('version-platform-manager.public_whats_new.url', 'whats-new'), '/');
            $onWhatsNewPage = $request->path() === mb_ltrim($whatsNewUrl, '/');

            if ($userNeedsUpdate) {
                // Avoid redirect loop
                if (!$onWhatsNewPage) {
                    return redirect($whatsNewUrl);
                }
            }
        }

        return $next($request);
    }
}
