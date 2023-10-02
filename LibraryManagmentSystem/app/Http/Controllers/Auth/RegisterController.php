<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class RegisterController extends Controller
{

    public function create(){
        return view('registration.register');
    }

    public function store(Request $request){
        $valideateData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password'=>'required|min:8',
            'role'=>'required'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;

        $user->save();
        return redirect('/');
    }


}
