<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifiyAccountController;
use Illuminate\Support\Facades\Route;

// Reigster a user
Route::post("/register", [AuthController::class, "register"]);

// Verify a user account
Route::post("/verify/{token}", [
    VerifiyAccountController::class,
    "verifyAccount",
]);
