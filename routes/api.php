<?php

use App\Http\Controllers\MessangerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/messanger/conversations',[MessangerController::class,'conversations']);
Route::get('/messanger/conversations/{id}',[MessangerController::class,'messages']);
Route::post('/messanger/{id}/send-message',[MessangerController::class,'store']);


