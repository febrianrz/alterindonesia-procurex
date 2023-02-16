<?php

namespace Alterindonesia\Procurex\Providers;

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
        //
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
    }
}
