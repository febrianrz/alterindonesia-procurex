<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            \App\Services\Module\ModuleServiceInterface::class,
            \App\Services\Module\ModuleServiceEloquent::class
        );
        $this->app->bind(
            \App\Services\Menu\MenuServiceInterface::class,
            \App\Services\Menu\MenuServiceEloquent::class
        );
        $this->app->bind(
            \App\Services\SubMenu\SubMenuServiceInterface::class,
            \App\Services\SubMenu\SubMenuServiceEloquent::class
        );
    }
}
