<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\MasterData\UserManagement\UserController::class)->prefix("user")->group(function () {
    Route::get("/", "index")->name("api.user.index");
    Route::post("/", "store")->name("api.user.store");
    Route::get(ID, "show")->name("api.user.show");
    Route::put(ID, "update")->name("api.user.update");
    Route::delete(ID, "destroy")->name("api.user.destroy");
    Route::put(RESTORE_PATH, "restore")->name("api.user.restore");
});
