<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

//*********************************/
//********* PUBLIC ROUTES *********/
//*********************************/
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
// Reset password request
Route::post("/forgotpassword", [
    ForgotPasswordController::class,
    "forgotPassword",
]);
// Verify a user account
Route::post("/verify/{token}", [
    VerifyAccountController::class,
    "verifyAccount",
]);
// Reset password handling
Route::post("/resetpassword/{code}", [
    ResetPasswordController::class,
    "resetPassword",
]);

//*********************************/
//****** USER PRIVATE ROUTES ******/
//*********************************/
Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post("/logout", [AuthController::class, "logout"]);
});
