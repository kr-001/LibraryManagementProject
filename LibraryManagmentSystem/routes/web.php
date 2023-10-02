<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Homepage\HomePageController;
use App\Http\Controllers\User\BorrowingsController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Auth\Events\Login;

Route::resource('books', BookController::class);
Route::resource('users', UserController::class);
Route::resource('borrowers', BorrowerController::class);
Route::resource('transactions', TransactionController::class);



Route::get('/' , [HomePageController::class , 'index'])->name('home');
Route::get('/loginUser',[LoginController::class , 'create'])->name('create');
Route::post('/register' , [AuthController::class, 'register'])->name('register');
Route::get('/registerUser',[RegisterController::class , 'create'])->name('registerUser');


Route::post('/login' , [AuthController::class , 'login'])->name('authUser'); 
Route::get('/redirectUser' , [AuthController::class , 'redirectToRoleSpecificContent'])->name('redirectUser');


Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard' , [DashboardController::class , 'dashboard'] )->name('dashboard');
    Route::get('/books' , [BookController::class , 'index']);
    Route::get('/adminPanel' ,[AdminController::class , 'index'])->name('adminPanel');
    Route::get('/checkout/{id}' , [CheckoutController::class , 'index'])->name('checkout');
    Route::post('/checkoutPage' , [CheckoutController::class , 'createOrder'])->name('checkoutPage');
    Route::get('/myBorrowings' , [BorrowingsController::class , 'index']);
  
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout' , [AuthController::class , 'logout'])->name('logout');
});
