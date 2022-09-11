<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Reigster a user
Route::post("/register", [AuthController::class, "register"]);

Route::post("/login", [AuthController::class, "login"]);

Route::post("/password/email", [
    ForgotPasswordController::class,
    "forgotPassword",
]);

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post("/logout", [AuthController::class, "logout"]);
});

// Verify a user account
Route::post("/verify/{token}", [
    VerifyAccountController::class,
    "verifyAccount",
]);

// Reset Password Route
Route::post("/resetpassword/{code}", [
    ResetPasswordController::class,
    "resetPassword",
]);
