<?php

use Illuminate\Support\Facades\Route;

if (!defined('ID')) {
    define("ID", "/{id}");
}
if (!defined('RESTORE_PATH')) {
    define("RESTORE_PATH", "/restore/{id}");
}

Route::middleware(['log.activity'])->group(function () {
    Route::get('/ping', function () {
        return response()->json([
            'message'   => 'ok'
        ]) ;
    });

    Route::post('/login', 'App\Http\Controllers\LoginController@doLogin')->name('api.login');

    Route::middleware(['auth.jwt'])->group(function () {
        Route::get('/profile', 'App\Http\Controllers\LoginController@profile')->name('api.profile');
        Route::get('/profile/key', 'App\Http\Controllers\LoginController@tokenKey')->name('api.profile.token-key');
        Route::post('/profile/refresh_token', 'App\Http\Controllers\LoginController@refreshToken')->name('api.profile.refresh_token');
        Route::post('/profile', 'App\Http\Controllers\LoginController@updateProfile')->name('api.update.profile');
        Route::post('/profile/password', 'App\Http\Controllers\LoginController@updatePassword')->name('api.update.profile-password');
        Route::post('/logout', 'App\Http\Controllers\LoginController@logout')->name('api.logout');

        # User Management
        require __DIR__."/UserManagement/role-api.php";
        require __DIR__."/UserManagement/permission-api.php";
        require __DIR__."/UserManagement/user-api.php";

        # Module Management
        require __DIR__."/ModuleManagement/module-api.php";
        require __DIR__."/ModuleManagement/menu-api.php";
        require __DIR__."/ModuleManagement/sub-menu-api.php";

        # Service Management
        require __DIR__."/ServiceManagement/service-api.php";

        # Employee Management
        require __DIR__."/EmployeeManagement/employee-api.php";
    });
});


