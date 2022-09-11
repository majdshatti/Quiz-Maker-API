<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Auth\ResetPasswordController;

// Reigster a user
Route::post("/register", [AuthController::class, "register"]);

//Route::post("/{locale}/login", [AuthController::class, "login"]);

Route::group(["middleware" => ["LanguageManager"]], function () {
    Route::post("/{lang}/login", [AuthController::class, "login"]);
});

Route::post("/password/email", [
    ForgotPasswordController::class,
    "forgotPassword",
]);

Route::post("/forgotpassword", [
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
