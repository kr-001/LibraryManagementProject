<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    { 
        $this->middleware('auth:api' , ['except' => ['login' , 'register']]);
      
    }

    public function login(Request $request)
    {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $userRole = $user->role;
        $token = JWTAuth::fromUser($user, ['email' => $user->email]);
        $response = ['token' => $token];

        // Depending on the user's role
        if ($userRole === 'Librarian') {
            $response['redirect'] = route('adminPanel');
        } elseif ($userRole === 'Student') {
            $response['redirect'] = route('dashboard');
        } else {
            $response['redirect'] = route('create');
        }

        return response()->json($response);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6',
            'role'=>'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role,
        ]);
        return $this->respondWithToken(Auth::attempt($request->only('email' , 'password')));
    }
   
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message'=>'Logged Out']);
    }

    public function redirectToRoleSpecificContent(Request $request)
    {
        $token = $request->header("Authorization");
        try{
            $payload = JWTAuth::getPayload($token);
            $userRole = $payload['role'];
            if ($userRole === 'admin') {
                return redirect()->route('adminPanel');
            } elseif ($userRole === 'user') {
                return redirect()->route('dashboard'); 
            } else {
                return redirect()->route('default.dashboard');
            }
        }catch(JWTException $e){
            return response()->json(['error' => 'Token is invalid'], 401);
        }
      
    }

    protected function respondWithToken($token)
    {
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => Auth::factory()->getTTL() * 60,
    ]);
    }
}
