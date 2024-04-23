<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'full_name' => 'required',
            'bio' => 'required|max:100',
            'password' => 'required|min:6',
            'username' => 'required|unique:users|min:3|regex:/^[a-zA-Z0-9._]+$/' //Ini Harus dipelajarin
        ]);

        $newUser = new User();
        $newUser->full_name = $request->full_name;
        $newUser->username = $request->username;
        $newUser->password = $request->password;
        $newUser->bio = $request->bio;
        $newUser->is_private = $request->is_private;
        $newUser->save();

        $user = User::where('username', $request->username)->first();
        $token = $user->createToken($request->username)->plainTextToken;

        return response()->json([
            'message' => 'Register Success',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function login(Request $request){
       $data =  $request->validate([
                'password' => 'required',
                 'username' => 'required'
                ]);


        if(Auth::attempt($data)){ //Logic Cek Login
            $acc = User::where('username', Auth::user()->username)->first();
            $token = $acc->createToken($acc->username)->plainTextToken; //Sintaks Buat Token

            return response()->json([
                "message" => "Login Success",
                'token' => $token,
                "user" => $acc
            ]);
        }

        return response()->json([
            'message' => 'Wrong username or password'
        ], 401);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete(); //Hafalin Sintaks ini
        return response()->json([
            'message' => 'Logout Success'
        ]);
    }
}
