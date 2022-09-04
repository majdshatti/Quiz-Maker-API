<?php

use Illuminate\Support\Facades\Route;

//*********************************/
//******** Public Routes **********/
//*********************************/

require_once "auth.php";

//*********************************/
//******** Private Routes *********/
//*********************************/

Route::group(["middleware" => ["auth:sanctum"]], function () {});
