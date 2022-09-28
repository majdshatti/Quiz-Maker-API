<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TakenQuizAnswerController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::group(["prefix" => "question"], function () {
        //*********************************/
        //****** USER PRIVATE ROUTES ******/
        //*********************************/
        Route::get("/", [QuestionController::class, "getQuestions"]);
        Route::get("/{uuid}", [QuestionController::class, "getQuestionByUuid"]);
        Route::post("/{uuid}/answer", [
            TakenQuizAnswerController::class,
            "submitAnswer",
        ]);

        Route::group(["middleware" => ["authorize"]], function () {
            //*********************************/
            //***** ADMIN PRIVATE ROUTES ******/
            //*********************************/

            Route::post("/", [QuestionController::class, "createQuestion"]);
            Route::put("/{uuid}", [QuestionController::class, "editQuestion"]);
            Route::delete("/{uuid}", [
                QuestionController::class,
                "deleteQuestion",
            ]);
        });
    });
});
