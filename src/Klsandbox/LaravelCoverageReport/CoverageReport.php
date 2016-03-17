<?php

namespace Klsandbox\LaravelCoverageReport;

use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use ReflectionMethod;

class CoverageReport extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coverage:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report the coverage for a run.';

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * An array of all the registered routes.
     *
     * @var \Illuminate\Routing\RouteCollection
     */
    protected $routes;

    public function __construct(Router $router) {
        parent::__construct();

        $this->router = $router;
        $this->routes = $router->getRoutes();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() {
        if (CoverageRun::getActiveRun()) {
            $this->error("Error: Active runs exists. Stop run before running report");
            return;
        }

        $run = CoverageRun::orderBy('created_at', 'DESC')
                ->first();
        
        if (!$run)
        {
            $this->error("No run found");
            return;
        }
        
        $runIds = [];
        array_push($runIds, $run->id);
        
        $actions = [];
        foreach ($this->routes as $route) {
            $actions[$route->getActionName()] = 0;
        }

        $records = CoverageRecord::
                whereIn('coverage_run_id', $runIds)
                ->where('category', '=', 'Route')
                ->get();

        foreach ($records as $record) {
            $actionName = $record->namespace . '@' . $record->name;
            $actions[$actionName] = 1;
        }

        $total = count($actions);
        $covered = 0;

        ksort($actions);

        foreach ($actions as $actionName => $count) {
            if (!$count) {
                list($namespace, $name) = explode('@', $actionName);
                if ($name == 'missingMethod') {
                    $method = new ReflectionMethod($namespace, $name);
                    $has = $method->class == $namespace;
                    if (!$has) {
                        continue;
                    }
                }

                $this->comment("Uncovered action " . $actionName);
            } else {
                $covered++;
            }
        }

        $this->comment("Route Coverage $covered/$total");
        
        $views = [];
        foreach (\Config::get('view.paths') as $folder)
        {
            $finder = \Symfony\Component\Finder\Finder::create()->in($folder);
            foreach($finder->files() as $i)
            {
               $views[$i->getRelativePathname()] = 0;
            }
        }
        
        $records = CoverageRecord::whereIn('coverage_run_id', $runIds)
                ->where('category', '=', 'View')
                ->get();
        
        foreach ($records as $record)
        {
            $views[$record->name] = 1; 
        }

        $total = count($views);
        $covered = 0;

        ksort($views);
        foreach ($views as $actionName => $count) {
            if (!$count) {
                $this->comment("Uncovered view " . $actionName);
            } else {
                $covered++;
            }
        }
        
        $this->comment("View Coverage $covered/$total");
    }

}
