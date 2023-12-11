<?php

namespace Alterindonesia\Procurex\Providers;

use Alterindonesia\Procurex\Console\CreateTaskNotifikasiCommand;
use Alterindonesia\Procurex\Factories\WordTemplateFactory;
use Alterindonesia\Procurex\Middleware\AuthJWTMiddleware;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Factory as HttpFactory;
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
        $this->mergeConfigFrom(__DIR__.'/../config/procurex.php', 'procurex');

        $this->registerWordTemplateService();
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

//        if($this->app->runningInConsole()) {
//            $this->commands([
//                CreateTaskNotifikasiCommand::class
//            ]);
//        }
    }

    private function registerWordTemplateService(): void
    {
        $this->app->singleton(WordTemplateFactory::class, function ($app) {
            $config = $app->make('config');

            return new WordTemplateFactory(
                $app[HttpFactory::class],
                $app->make(Filesystem::class),
                $config->get('procurex.media_service_base_url'),
                $config->get('procurex.access_token'),
            );
        });
    }
}
