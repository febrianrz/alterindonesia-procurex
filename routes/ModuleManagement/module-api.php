<?php

use App\Http\Controllers\MasterData\ModuleManagement\ModuleController;
use Illuminate\Support\Facades\Route;

Route::controller(ModuleController::class)->prefix("module")->group(function () {
    Route::get("/", "index")->name("api.module.index");
    Route::post("/", "store")->name("api.module.store");
    Route::get(ID, "show")->name("api.module.show");
    Route::put(ID, "update")->name("api.module.update");
    Route::delete(ID, "destroy")->name("api.module.destroy");
    Route::put(RESTORE_PATH, "restore")->name("api.module.restore");

    Route::get("/menu-list/{module_path}","getMenu")->name("api.module.menu");
});
