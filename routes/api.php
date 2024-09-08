<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\IndexController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthenticationController::class, 'register'])
        ->name('register');

    Route::post('/login', [AuthenticationController::class, 'login'])
        ->name('login');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/follower', [ConnectionController::class, 'sendFollowerRequest']);

    Route::post('/decision', [ConnectionController::class, 'decideOnRequest']);

    Route::get('/search', [ConnectionController::class, 'viewFollowers']);

    Route::post('/account', [AccountController::class, 'createAccount']);

    Route::get('/account', [AccountController::class, 'viewAccounts']);

    Route::get('/account/{id}', [AccountController::class, 'viewAccount']);




});



Route::get('/', [IndexController::class, 'get']);





//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
