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
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    public function authenticate(Request $request){
        $credentials = $request->only('email' , 'password');
        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error'=>'invalid_credentials'],400);
            }
        }catch(JWTException $e){
            return response()->json(['error'=>'could_not_create_token'],500);
        }
        return response()->json(compact('token'));
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
        $credentials = $validator->validated();
        if(!$token = JWTAuth::attempt($credentials))
        {
            return response()->json(['error'=>'Unauthorized'],401);
        }
        
        return $this->respondWithToken($token);

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
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user' , 'token'),201);
    }

    public function userProfile(){
        return response()->json(JWTAuth::user());
    }


    public function getAuthenticatedUser()
    {
            try {

                    if (! $user = JWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            return response()->json(compact('user'));
    }




    protected function respondWithToken($token)
{
    $response = [
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
        'user' => new UserResource(JWTAuth::user())
    ];
    return response()->json($response);
}


    protected function createNewToken($token)
    {
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL()*60,
        'user'=> new UserResource(auth()->user())
    ]);
    }
    
    public function logout(){
        auth()->logout();
        return response()->json(['message'=>"User logged out successfully!"]);
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