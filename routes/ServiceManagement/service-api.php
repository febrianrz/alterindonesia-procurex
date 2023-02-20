<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\MasterData\ServiceManagement\ServiceController::class)->prefix("service")->group(function () {
    Route::get("/", "index")->name("api.service.index");
    Route::post("/", "store")->name("api.service.store");
    Route::get(ID, "show")->name("api.service.show");
    Route::put(ID, "update")->name("api.service.update");
    Route::delete(ID, "destroy")->name("api.service.destroy");
    Route::put(RESTORE_PATH, "restore")->name("api.service.restore");
});
