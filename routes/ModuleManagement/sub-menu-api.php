<?php

use App\Http\Controllers\MasterData\ModuleManagement\SubMenuController;
use Illuminate\Support\Facades\Route;

Route::controller(SubMenuController::class)->prefix("sub_menu")->group(function () {
    Route::get("/", "index")->name("api.sub_menu.index");
    Route::post("/", "store")->name("api.sub_menu.store");
    Route::get(ID, "show")->name("api.sub_menu.show");
    Route::put(ID, "update")->name("api.sub_menu.update");
    Route::delete(ID, "destroy")->name("api.sub_menu.delete");
    Route::put(RESTORE_PATH, "restore")->name("api.sub_menu.restore");
});
