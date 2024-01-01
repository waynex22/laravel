<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if ($request->user() && $request->user()->hasAnyRole($roles)) {
            return $next($request);
        }
        abort(403, 'bạn không có quyền truy cập');
    }
}
