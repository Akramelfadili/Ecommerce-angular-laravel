<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any("add",[ProductController::class,'add']);
Route::any("update",[ProductController::class,'update']);
Route::any("delete",[ProductController::class,'delete']);
Route::any("show",[ProductController::class,'show']);

Route::any("register",[UserController::class , "register"]);
Route::any("login",[UserController::class , "login"]);