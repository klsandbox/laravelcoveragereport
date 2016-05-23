<?php

namespace Klsandbox\LaravelCoverageReport;

use Illuminate\Support\ServiceProvider;

class LaravelCoverageReportServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (\Config::get('coverage.enabled')) {
            $app = $this->app;
            $resolver = $app['view.engine.resolver'];
            $resolver->register('blade', function () use ($app) {
                $cache = $app['config']['view.compiled'];
                $bladeCompiler = new CoverageBladeCompiler($app['files'], $cache);

                return new \Illuminate\View\Engines\CompilerEngine($bladeCompiler, $app['files']);
            });
        }

        $this->app->singleton('command.klsandbox.coveragerunstart', function ($app) {
            return new CoverageRunStart();
        });

        $this->app->singleton('command.klsandbox.coveragerunstop', function ($app) {
            return new CoverageRunStop();
        });

        $this->app->singleton('command.klsandbox.coveragereport', function ($app) {
            $router = $app['router'];

            return new CoverageReport($router);
        });

        $this->commands('command.klsandbox.coveragerunstart');
        $this->commands('command.klsandbox.coveragerunstop');
        $this->commands('command.klsandbox.coveragereport');

        if (\Config::get('coverage.enabled')) {
            app('events')->listen('kernel.handled', function ($request, $response) {
                $recorder = new RecordRouteCoverage();
                $recorder->record($request);
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.klsandbox.coveragerunstart',
            'command.klsandbox.coveragerunstop',
            'command.klsandbox.coveragereport',
        ];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../database/migrations/' => database_path('/migrations'),
                ], 'migrations');

        $this->publishes([
            __DIR__ . '/../../../config/' => config_path(),
                ], 'config');
    }
}
