<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
Route::post('login', [AuthController::class , 'login'])->name('login');
Route::get('open', [DataController::class , 'open']);


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/verify' , [AuthController::class , 'getAuthenticatedUser']);
    Route::get('/dashboard' , [DashboardController::class , 'index']);
    Route::post('/logout' , [AuthController::class , 'logout'])->name('logout');
    Route::get('/user', [AuthController::class , 'getAuthenticatedUser']);  
    Route::get('closed', [DataController::class , 'closed']);
});
