<?php

declare(strict_types=1);

namespace Canvas\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class Admin
{
    /**
     * Handle the incoming request.
     *
     * @param $request
     * @param $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $request->user('canvas')->isAdmin ? $next($request) : abort(403);
    }
}
