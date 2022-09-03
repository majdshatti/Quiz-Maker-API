<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//*********************************/
//******** Public Routes **********/
//*********************************/

require_once "auth.php";

//*********************************/
//******** Private Routes *********/
//*********************************/

Route::group(["middleware" => ["auth:sanctum"]], function () {});
