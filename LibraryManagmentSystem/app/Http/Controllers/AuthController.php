<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['login' , 'register']]);

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|string|email',
            'password'=>'required|string|min:6'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(),400);
        }

        if(!$token = auth()->attempt($validator->validate()))
        {
            return response()->json(['error'=>'Unauthorized'],401);
        }
        // Redirection on the basis of User's role
        $user = auth()->user();
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'role'=> $user->role,
        ];
        return response()->json(['token' => $token , 'user' => $userData]);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6|confirmed',
            'role'=>'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
        ]);
        return response()->json([
            'message'=>'User Registered Successfully.',
            'user'=>$user,
        ]);
    }

    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message'=>'Logged Out']);
    }

    public function userProfile(){
        return response()->json(auth()->user());
    }


    protected function createNewToken($token)
    {
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL()*60,
        'user'=>auth()->user()
    ]);
    }



    public function verifyToken()
{
    try {
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json(['error' => 'Token Not Provided'], 401);
        }

        $decodedToken = JWTAuth::parseToken()->authenticate();
        if (!$decodedToken) {
            return response()->json(['error' => 'Token is Invalid'], 401);
        }
        return response()->json(['data' => $decodedToken]);
    } catch (Exception $e) {
        return response()->json(['error' => 'Token is Invalid'], 401);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Token is Expired'], 401);
    }
}
}
