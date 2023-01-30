<?php

use Illuminate\Support\Facades\Route;

const ID = "/{id}";
const RESTORE_PATH = "/restore/{id}";

Route::middleware(['log.activity'])->group(function () {
    Route::get('/ping', function () {
        return response()->json([
            'message'   => 'ok'
        ]) ;
    });
    Route::post('/login', 'App\Http\Controllers\LoginController@doLogin')->name('api.login');

    Route::middleware(['auth.jwt'])->group(function () {
        Route::get('/profile', 'App\Http\Controllers\LoginController@profile')->name('api.profile');
        Route::post('/logout', 'App\Http\Controllers\LoginController@logout')->name('api.logout');

        # User Management
        require __DIR__."/UserManagement/role-api.php";
        require __DIR__."/UserManagement/permission-api.php";

        # Module Management
        require __DIR__."/ModuleManagement/module-api.php";
        require __DIR__."/ModuleManagement/menu-api.php";
        require __DIR__."/ModuleManagement/sub-menu-api.php";
    });
});


