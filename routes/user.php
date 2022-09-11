<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::put("/user/{slug}/changepassword", [
        UserController::class,
        "changePassword",
    ]);
    Route::put("/user/{slug}/edituser", [UserController::class, "edituser"]);
    Route::delete("/user/{slug}/deleteuser", [
        UserController::class,
        "deleteUser",
    ]);
    Route::get("/user", [UserController::class, "getUsers"]);
});
