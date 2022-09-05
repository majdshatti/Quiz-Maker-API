<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\VerifyAccountController;
use Illuminate\Support\Facades\Route;

// Reigster a user
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post("/logout", [AuthController::class, "logout"]);
});

// Verify a user account
Route::post("/verify/{token}", [
    VerifyAccountController::class,
    "verifyAccount",
]);
