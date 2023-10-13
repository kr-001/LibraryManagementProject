<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\LoginController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class , 'register']);
Route::post('login', [AuthController::class , 'authenticate']);
Route::get('open', [DataController::class , 'open']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', [AuthController::class , 'getAuthenticatedUser']);
    Route::get('closed', [DataController::class , 'closed']);
});

// Route::group(['middleware' => 'api'], function ($routes) {
//     Route::get('/user-profile' , [AuthController::class , 'userProfile'])->name('user-profile');
//     Route::post('/registerUser' , [AuthController::class , 'register'])->name('register');
//     Route::post('/logout' , [AuthController::class , 'logout'])->name('logout');
//     Route::post('/loginUser' , [AuthController::class , 'login'])->name('login');
// });
