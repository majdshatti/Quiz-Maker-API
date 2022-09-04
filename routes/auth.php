<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthController::class, "register"]);
Route::post('/login',[AuthController::class,'login']);

Route::group(["middleware" => ["auth:sanctum"]], function () {

    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/changepassword',[UserController::class,'changePassword']);
});