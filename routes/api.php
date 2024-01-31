<?php

use App\Http\Controllers\AuthRegisterController;
use App\Http\Controllers\DemandController;
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
|--------------------------------------------------------------------------
|Basic route: http://localhost:8000/api/xxx
|localhost port depends on .env : REDIS.PORT
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthRegisterController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')
    ->group(function() {
        Route::controller(RepositoryController::class)->group(function () {
            Route::post('/demand',  'store');
            Route::get('/get-repositories', "getRepositories");
        });

        Route::controller(UsersController::class)->prefix('/user')->group(function () {
            Route::get('/get-authenticated', 'getAuthUser');
            Route::post('/update', 'update');
        });
    });

