<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get("/user", [UserController::class, "getUsers"]);
    Route::put("/user/{slug}/changepassword", [
        UserController::class,
        "changePassword",
    ]);
});
