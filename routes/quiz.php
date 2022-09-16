<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::group(["prefix" => "quiz"], function () {
        Route::get("/", [QuizController::class, "getQuiz"]);
        Route::get("/{slug}", [QuizController::class, "getQuizBySlug"]);

        //*********************************/
        //****** USER PRIVATE ROUTES ******/
        //*********************************/

        Route::group(["middleware" => ["authorize"]], function () {
            //*********************************/
            //***** ADMIN PRIVATE ROUTES ******/
            //*********************************/
            Route::post("/", [QuizController::class, "createQuiz"]);
            Route::put("/{slug}", [QuizController::class, "editQuiz"]);
            Route::delete("/{slug}", [QuizController::class, "deleteQuiz"]);
        });
    });
});
