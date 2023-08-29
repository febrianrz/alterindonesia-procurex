<?php

namespace Alterindonesia\Procurex\Providers;

use Alterindonesia\Procurex\Console\ClearLogCommand;
use Alterindonesia\Procurex\Console\CreateTaskNotifikasiCommand;
use Alterindonesia\Procurex\Middleware\AuthJWTMiddleware;
use Illuminate\Support\ServiceProvider;

class AlterindonesiaProcurexProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Webklex\PDFMerger\Providers\PDFMergerServiceProvider::class);
        $this->app->alias('PDFMerger', \Webklex\PDFMerger\Facades\PDFMergerFacade::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/procurex.php' => config_path('procurex.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['router']->aliasMiddleware('auth.jwt', AuthJWTMiddleware::class);
        $this->app['router']->aliasMiddleware('log.activity', AuthJWTMiddleware::class);

        if($this->app->runningInConsole()) {
            $this->commands([
                ClearLogCommand::class
            ]);
        }
    }
}
