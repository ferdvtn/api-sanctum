<?php

use App\Http\Controllers\Api\AuthController;
use Facade\Ignition\DumpRecorder\DumpHandler;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::resource('auth', AuthController::class);
Route::prefix('v1')->group(function () {
    Route::prefix('user')->group(function () {

        Route::post('register', [AuthController::class, 'store']);
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:api')->group(function () {
            Route::get('detail', [AuthController::class, 'show']);
            Route::patch('update', [AuthController::class, 'update']);
            Route::delete('logout', [AuthController::class, 'destroy']);
        });
    });
});
