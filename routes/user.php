<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

//*********************************/
//****** USER PRIVATE ROUTES ******/
//*********************************/
Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::put("/user/{slug}/changepassword", [
        UserController::class,
        "changePassword",
    ]);
    Route::put("/user/{slug}", [UserController::class, "edituser"]);
    Route::delete("/user/{slug}", [UserController::class, "deleteUser"]);
    Route::get("/user/profile", [UserController::class, "getLoggedUser"]);

    //*********************************/
    //***** ADMIN PRIVATE ROUTES ******/
    //*********************************/
    Route::group(["middleware" => ["authorize"]], function () {
        Route::get("/user", [UserController::class, "getUsers"]);
        Route::get("/user/{slug}", [UserController::class, "getUserBySlug"]);
    });
});
