<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/api',function(){
    Route::get('/api/pings',function(){
        return "ok";
    });
});

