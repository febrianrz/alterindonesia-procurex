<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['log.activity'])->group(function(){
    Route::get('/ping',function(){
        return response()->json([
            'message'   => 'ok'
        ]) ;
    });
    Route::post('/login','App\Http\Controllers\LoginController@doLogin')->name('api.login');


    Route::middleware(['auth.jwt'])->group(function(){
        Route::get('/profile','App\Http\Controllers\LoginController@profile')->name('api.profile');
        Route::post('/logout','App\Http\Controllers\LoginController@logout')->name('api.logout');

        Route::middleware(['auth.jwt.permission'])->group(function(){
            Route::get('/users','App\Http\Controllers\UserController@index')->name('api.users.index');
            Route::get('/users/{id}','App\Http\Controllers\UserController@show')->name('api.users.show');
        });

    });
});


