<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
class LoginController extends Controller
{
    public function create(){
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $userRole = $user->role;
            $token = JWTAuth::fromUser($user, ['role'=>$user->role]);
            if ($userRole === 'admin') {
                return redirect()->route('adminPanel');
            } elseif ($userRole === 'user') {
                return redirect()->route('dashboard'); 
            } else {
                return redirect()->route('create');
            }
            return response()->json(['token'=>$token]);
        }
    
        return response()->json(['error' => 'Unauthorized'], 401);
    }

   public function logout(Request $request)
   {
       Auth::logout();
       $request->session()->invalidate();//invalidate the session
       $request->session()->regenerateToken();//Regenerate CSRF token

       //success message
       return redirect('/login')->with('success' , 'Logged Out Successfully');
   }
}
