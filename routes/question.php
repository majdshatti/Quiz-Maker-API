<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::group(["prefix" => "question"], function () {
        //*********************************/
        //****** USER PRIVATE ROUTES ******/
        //*********************************/
        Route::get("/", [QuestionController::class, "getQuestions"]);
        Route::get("/{slug}", [QuestionController::class, "getQuestionBySlug"]);

        Route::group(["middleware" => ["authorize"]], function () {
            //*********************************/
            //***** ADMIN PRIVATE ROUTES ******/
            //*********************************/
            Route::post("/", [QuestionController::class, "createQuestion"]);
            Route::put("/{slug}", [QuestionController::class, "editQuestion"]);
            Route::delete("/{slug}", [
                QuestionController::class,
                "deleteQuestion",
            ]);
        });
    });
});
