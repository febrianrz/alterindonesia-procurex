<?php

namespace App\Providers;

use App\Contracts\RestApiContract;
use App\Http\Controllers\MasterData\UserManagement\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;
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
        $this->app->when(UserController::class)
           ->needs(RestApiContract::class)
           ->give(function(){
               return new UserService(User::class,UserResource::class);
           });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        # Start User Management
        // Role Binding
        $this->app->when(\App\Http\Controllers\MasterData\UserManagement\RoleController::class)
            ->needs(\App\Services\MasterData\MasterDataServiceInterface::class)
            ->give(\App\Services\MasterData\UserManagement\RoleServiceEloquent::class);

        // Permission Binding
        $this->app->when(\App\Http\Controllers\MasterData\UserManagement\PermissionController::class)
            ->needs(\App\Services\MasterData\MasterDataServiceInterface::class)
            ->give(\App\Services\MasterData\UserManagement\PermissionServiceEloquent::class);

        // User Binding
        $this->app->when(\App\Http\Controllers\MasterData\UserManagement\UserController::class)
            ->needs(\App\Services\MasterData\MasterDataServiceInterface::class)
            ->give(\App\Services\MasterData\UserManagement\UserServiceEloquent::class);
        # End User Management

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

        # Start Service management
        $this->app->when(\App\Http\Controllers\MasterData\ServiceManagement\ServiceController::class)
            ->needs(\App\Services\MasterData\MasterDataServiceInterface::class)
            ->give(\App\Services\MasterData\ServiceManagement\ServiceEloquent::class);
        # End Service Management
    }
}
