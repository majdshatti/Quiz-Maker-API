<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::group(["middleware" => ["auth:sanctum"]], function () {

    Route::put("/user/{slug}/changepassword", [UserController::class, "changePassword"]);
    
    Route::put("/user/{slug}", [UserController::class, "editUser"]);

    Route::delete("/user/{slug}", [UserController::class, "deleteUser"]);

});