<?php

use App\Http\Controllers\AuthRegisterController;
use App\Http\Controllers\ForgottenPasswordController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TestRequestController;
use App\Http\Controllers\UsersController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});


    Route::controller(ForgottenPasswordController::class)->group(function() {
      Route::post('/forgot-password', 'requestPasswordResetForm');
      Route::post('/reset-password', 'resetPasswordForm');
    });

/*Route::controller(TestRequestController::class)->group(function () {
Route::post('/run-tests', 'runTests');
});*/
//Route::get('/git-fetch-origin', [RepositoryController::class, 'gitFetchOrigin']);
Route::get('/get-results-by-branch',[ResultController::class, 'getResultByBranch']);
Route::post('/run-tests', [TestRequestController::class, 'runTests']);
Route::post('/demand', [RepositoryController::class, 'store']);

Route::controller(AuthRegisterController::class)->group(function () {
  Route::post('/register', 'register');
  Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')
  ->group(function () {
    Route::controller(RepositoryController::class)->group(function () {
//Route::post('/demand',  'store');
      Route::get('/get-repositories', 'getRepositories');
      Route::get('/git-fetch-origin', 'gitFetchOrigin');
      Route::get('/get-repository', 'getRepository');
    });

    Route::controller(AuthRegisterController::class)->group(function() {
      Route::get('/delete-account', 'deleteUser');
      Route::post('/logout', 'logout');
    });

    Route::controller(UsersController::class)->prefix('/user')->group(function () {
      Route::get('/get-authenticated', 'getAuthUser');
      Route::post('/update', 'update');
    });

    Route::controller(TestRequestController::class)->group(function () {
      Route::get('/get-tests-requests', 'getTestsRequests');
//Route::post('/run-tests', 'runTests');
    });
  });
