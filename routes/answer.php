<?php

use App\Http\Controllers\AnswerController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::group(["prefix" => "answer"], function () {
        Route::get("/", [AnswerController::class, "getAnswer"]);

        //*********************************/
        //****** USER PRIVATE ROUTES ******/
        //*********************************/

        Route::group(["middleware" => ["authorize"]], function () {
            //*********************************/
            //***** ADMIN PRIVATE ROUTES ******/
            //*********************************/
            Route::post("/", [AnswerController::class, "addAnswer"]);
            Route::put("/{slug}", [AnswerController::class, "editAnswer"]);
            Route::delete("/{slug}", [AnswerController::class, "deleteAnswer"]);
        });
    });
});
