<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//*********************************/
//******** Public Routes **********/
//*********************************/

require_once "auth.php";

//*********************************/
//******** Private Routes *********/
//*********************************/

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post("/logout", [UserController::class, "logout"]);
    Route::post("/changepassword", [UserController::class, "changePassword"]);
});

//*********************************/
//******** 404 Not Found **********/
//*********************************/

Route::fallback(function () {
    return response()->json(
        [
            "message" => "Page Not Found. Got lost? contact info@company.com",
        ],
        404
    );
});
