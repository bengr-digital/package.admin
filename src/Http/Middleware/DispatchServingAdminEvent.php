<?php

namespace Bengr\Admin\Http\Middleware;

use Bengr\Admin\Events\ServingAdmin;
use Closure;
use Illuminate\Http\Request;

class DispatchServingAdminEvent
{
    public function handle(Request $request, Closure $next)
    {
        ServingAdmin::dispatch();

        return $next($request);
    }
}
