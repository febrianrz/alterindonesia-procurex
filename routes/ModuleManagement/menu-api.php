<?php

use App\Http\Controllers\MasterData\ModuleManagement\MenuController;
use Illuminate\Support\Facades\Route;

Route::controller(MenuController::class)->prefix("menu")->group(function () {
    Route::get("/", "index")->name("api.menu.index");
    Route::post("/", "store")->name("api.menu.store");
    Route::get(ID, "show")->name("api.menu.show");
    Route::put(ID, "update")->name("api.menu.update");
    Route::delete(ID, "destroy")->name("api.menu.delete");
    Route::put(RESTORE_PATH, "restore")->name("api.menu.restore");
});
