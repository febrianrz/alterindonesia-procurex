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

        Route::middleware([
            \Alterindonesia\Procurex\Middleware\AuthJWTMiddleware::class,
            \Alterindonesia\Procurex\Middleware\ActivityLogMiddleware::class
        ])->group(function () {
            // List all routes
            Route::get('/routes','\Alterindonesia\Procurex\Controllers\AlterindonesiaProcurexController@getRouteList');
            if(config('procurex.is_sso_service')) {
                Route::post('/routes/assign', '\Alterindonesia\Procurex\Controllers\AlterindonesiaProcurexController@assignRoleRoute')->name('api.route.assign');
            }

        });

});

