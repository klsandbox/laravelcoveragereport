<?php

namespace Klsandbox\LaravelCoverageReport;

use Route;
use Closure;

class RecordRouteCoverage {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $response = $next($request);

        $route = Route::getCurrentRoute();

        list($namespace, $name) = explode('@', $route->getActionName());

        $record = CoverageRecord::RecordEntry('Route', $namespace, $name);
        
        return $response;
    }

}
