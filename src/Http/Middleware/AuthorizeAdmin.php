<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class AuthorizeAdmin
{
    /**
     * Deny (404) unless the host app defines and grants the `viewVersionManager` gate.
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(Gate::allows('viewVersionManager'), 404);

        return $next($request);
    }
}
