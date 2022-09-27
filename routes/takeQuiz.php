<?php

use App\Http\Controllers\TakenQuizController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::group(["prefix" => "/subject/{subjectSlug}/quiz/{quizSlug}/"], function () {
        //*********************************/
        //****** USER PRIVATE ROUTES ******/
        //*********************************/
        Route::post("take", [
            TakenQuizController::class, "takeQuiz"
        ]);

        Route::group(["middleware" => ["authorize"]], function () {
            //*********************************/
            //***** ADMIN PRIVATE ROUTES ******/
            //*********************************/

            Route::get("/", [
                TakenQuizController::class, "getQuizTaken"
            ]);
        });
    });
});
