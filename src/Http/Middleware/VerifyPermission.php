<?php

declare(strict_types=1);

namespace Canvas\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyPermission
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $request->user('canvas')->isAdmin || $request->id === $request->user('canvas')->id ? $next($request) : abort(403);
    }
}
