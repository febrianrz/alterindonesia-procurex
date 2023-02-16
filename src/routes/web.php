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

    // List all routes
    Route::get('/routes',function(){
        $routeCollection = Illuminate\Support\Facades\Route::getRoutes();
        $routes = [];
        foreach ($routeCollection as $value) {
            if (str_starts_with($value->getName(), 'api.')){
                $routes[] = $value->getName();
            }
        }
        return \Alterindonesia\Procurex\Facades\GlobalHelper::responseSuccess("Success",$routes);

    });
});

