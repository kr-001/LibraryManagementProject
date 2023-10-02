<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (auth()->check()) {
            $user = Auth::user();
            $books = Book::all();
            return view('user.dashboard', compact('user', 'books'));
        } else {
            return redirect('home');
        }
    }
    
}
