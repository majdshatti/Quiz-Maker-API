<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\App;

// Reigster a user
Route::post("/register", [AuthController::class, "register"]);

Route::post("/login", [AuthController::class, "login"]);

Route::post("/forgotpassword",  [ForgotPasswordController::class,"forgotPassword"]);

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    
});

// Verify a user account
Route::post("/verify/{token}", [
    VerifyAccountController::class,
    "verifyAccount",
]);
