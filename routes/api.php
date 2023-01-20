<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const ID = "/{id}";
const RESTORE_PATH = "/restore/{id}";

Route::middleware(['log.activity'])->group(function(){
    Route::get('/ping',function(){
        return response()->json([
            'message'   => 'ok'
        ]) ;
    });
    Route::post('/login','App\Http\Controllers\LoginController@doLogin')->name('api.login');

    Route::prefix("module")->group(function () {
        Route::get("/", "App\Http\Controllers\ModuleController@index")->name("api.list.module");
        Route::post("/", "App\Http\Controllers\ModuleController@store")->name("api.create.module");
        Route::get(ID, "App\Http\Controllers\ModuleController@show")->name("api.detail.module");
        Route::put(ID, "App\Http\Controllers\ModuleController@update")->name("api.update.module");
        Route::delete(ID, "App\Http\Controllers\ModuleController@destroy")->name("api.delete.module");
        Route::put(RESTORE_PATH, "App\Http\Controllers\ModuleController@restore")->name("api.restore.module");
    });

    Route::prefix("menu")->group(function () {
        Route::get("/", "App\Http\Controllers\MenuController@index")->name("api.list.menu");
        Route::post("/", "App\Http\Controllers\MenuController@store")->name("api.create.menu");
        Route::get(ID, "App\Http\Controllers\MenuController@show")->name("api.detail.menu");
        Route::put(ID, "App\Http\Controllers\MenuController@update")->name("api.update.menu");
        Route::delete(ID, "App\Http\Controllers\MenuController@destroy")->name("api.delete.menu");
        Route::put(RESTORE_PATH, "App\Http\Controllers\MenuController@restore")->name("api.restore.menu");
    });

    Route::prefix("sub_menu")->group(function () {
        Route::get("/", "App\Http\Controllers\SubMenuController@index")->name("api.list.sub_menu");
        Route::post("/", "App\Http\Controllers\SubMenuController@store")->name("api.create.sub_menu");
        Route::get(ID, "App\Http\Controllers\SubMenuController@show")->name("api.detail.sub_menu");
        Route::put(ID, "App\Http\Controllers\SubMenuController@update")->name("api.update.sub_menu");
        Route::delete(ID, "App\Http\Controllers\SubMenuController@destroy")->name("api.delete.sub_menu");
        Route::put(RESTORE_PATH, "App\Http\Controllers\SubMenuController@restore")->name("api.restore.sub_menu");
    });

    Route::middleware(['auth.jwt'])->group(function(){
        Route::get('/profile','App\Http\Controllers\LoginController@profile')->name('api.profile');
        Route::post('/logout','App\Http\Controllers\LoginController@logout')->name('api.logout');
    });
});


