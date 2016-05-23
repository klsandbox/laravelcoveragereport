<?php

namespace Klsandbox\LaravelCoverageReport;

use Route;

class RecordRouteCoverage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function record($request)
    {
        $route = Route::getCurrentRoute();

        list($namespace, $name) = explode('@', $route->getActionName());

        $record = CoverageRecord::RecordEntry('Route', $namespace, $name);
    }
}
