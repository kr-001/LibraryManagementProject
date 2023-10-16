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
Route::get('/userLogin' , [LoginController::class , 'create'])->name('create');
Route::get('/registerForm' , [RegisterController::class , 'create'])->name('registerUser');
Route::get('/studentDashboard' , [DashboardController::class , 'index'])->name('user.dashboard')->middleware('jwt.verify');
Route::get('/checkout/{id}' , [CheckoutController::class , 'index'])->name('checkout')->middleware('jwt.verify');

Route::middleware(['auth'])->group(function(){
    Route::get('/books' , [BookController::class , 'index']);
    Route::get('/adminPanel' ,[AdminController::class , 'index'])->name('adminPanel');

    Route::post('/checkoutPage' , [CheckoutController::class , 'createOrder'])->name('checkoutPage');
    Route::get('/myBorrowings' , [BorrowingsController::class , 'index']);
    Route::get('/addBook' , [BookController::class , 'create'])->name('addBook');
    Route::post('/storeBook' , [BookController::class , 'store'])->name('storeBook');
  
});

Route::middleware(['jwt.auth'])->group(function(){
 
    Route::get('/verifyToken' , [AuthController::class , 'verifyToken'])->name('verifyToken');
});

