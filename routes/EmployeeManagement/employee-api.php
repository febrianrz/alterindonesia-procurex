<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\MasterData\EmployeeManagement\EmployeeController::class)->prefix("employee")->group(function () {
    Route::get("/superior", "superior")->name("api.employee.superior");
});
