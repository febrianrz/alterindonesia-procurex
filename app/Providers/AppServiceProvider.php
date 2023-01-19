<?php

namespace App\Providers;

use App\Contracts\RestApiContract;
use App\Http\Controllers\UserController;
use App\Services\User\UserService;
use Illuminate\Http\Resources\Json\JsonResource;
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
               return new UserService();
           });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}
