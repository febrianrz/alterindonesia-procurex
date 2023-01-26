<?php

use App\Http\Controllers\MasterData\UserManagement\PermissionController;
use Illuminate\Support\Facades\Route;

Route::controller(PermissionController::class)->prefix("permission")->group(function () {
    Route::get("/", "index")->name("api.permission.index");
    Route::post("/", "store")->name("api.permission.store");
    Route::get(ID, "show")->name("api.permission.show");
    Route::put(ID, "update")->name("api.permission.update");
    Route::delete(ID, "destroy")->name("api.permission.destroy");
    Route::put(RESTORE_PATH, "restore")->name("api.permission.restore");
});
