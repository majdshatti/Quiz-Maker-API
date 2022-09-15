<?php

use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::group(["prefix" => "subject"], function () {
        //*********************************/
        //****** USER PRIVATE ROUTES ******/
        //*********************************/
        Route::get("/", [SubjectController::class, "getSubjects"]);
        Route::get("/{slug}", [SubjectController::class, "getSubjectBySlug"]);

        Route::group(["middleware" => ["authorize"]], function () {
            //*********************************/
            //***** ADMIN PRIVATE ROUTES ******/
            //*********************************/
            Route::post("/", [SubjectController::class, "createSubject"]);
            Route::put("/{slug}", [SubjectController::class, "editSubject"]);
            Route::delete("/{slug}", [
                SubjectController::class,
                "deleteSubject",
            ]);
        });
    });
});
