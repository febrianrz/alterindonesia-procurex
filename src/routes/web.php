<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/api'], function(){
    Route::get('/ping', function () {
        return response()->json([
            'message'   => 'ok'
        ]) ;
    });
    Route::get('/time', function(){
        return response()->json([
            'timestamp'  => time(),
            'datetime'   => \Carbon\Carbon::now()->format("d F Y, H:i:s")
        ]);
    });

    Route::middleware(['log.activity'])->group(function () {
        Route::middleware(['auth.jwt'])->group(function () {
            // List all routes
            Route::get('/routes',function(){
                $routes = \Alterindonesia\Procurex\Facades\GlobalHelper::generateRolePermissions();
                return \Alterindonesia\Procurex\Facades\GlobalHelper::responseSuccess("Success",$routes);
            });

        });
    });

});

