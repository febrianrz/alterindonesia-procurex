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
        # Start Module Management
        // Module Binding
        $this->app->when(\App\Http\Controllers\MasterData\ModuleManagement\ModuleController::class)
            ->needs(\App\Services\MasterData\MasterDataServiceInterface::class)
            ->give(\App\Services\MasterData\ModuleManagement\ModuleServiceEloquent::class);
        // Menu Binding
        $this->app->when(\App\Http\Controllers\MasterData\ModuleManagement\MenuController::class)
            ->needs(\App\Services\MasterData\MasterDataServiceInterface::class)
            ->give(\App\Services\MasterData\ModuleManagement\MenuServiceEloquent::class);
        // SubMenu Binding
        $this->app->when(\App\Http\Controllers\MasterData\ModuleManagement\SubMenuController::class)
            ->needs(\App\Services\MasterData\MasterDataServiceInterface::class)
            ->give(\App\Services\MasterData\ModuleManagement\SubMenuServiceEloquent::class);
        # End Module Management
    }
}
