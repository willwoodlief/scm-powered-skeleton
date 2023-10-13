<?php

namespace App\Http\Middleware;

use App\Plugins\Plugin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use TorMorten\Eventy\Facades\Eventy;

class RouteEvents
{

    public function handle(Request $request, Closure $next): Response
    {
        Eventy::action(Plugin::ACTION_ROUTE_STARTED,$request);
        return $next($request);
    }
}
