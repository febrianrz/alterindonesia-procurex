<?php

use App\Http\Controllers\MasterData\UserManagement\RoleController;
use Illuminate\Support\Facades\Route;

Route::controller(RoleController::class)->prefix("role")->group(function () {
    Route::get("/", "index")->name("api.role.index");
    Route::post("/", "store")->name("api.role.store");
    Route::get(ID, "show")->name("api.role.show");
    Route::put(ID, "update")->name("api.role.update");
    Route::delete(ID, "destroy")->name("api.role.destroy");
    Route::put(RESTORE_PATH, "restore")->name("api.role.restore");
});
