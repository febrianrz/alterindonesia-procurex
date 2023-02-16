<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/api',function(){
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
});

