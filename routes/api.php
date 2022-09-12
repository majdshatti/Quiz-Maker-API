<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '{lang}','middleware' => 'LanguageManager'],function(){

    require_once "auth.php";
    require_once "user.php";
    
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
