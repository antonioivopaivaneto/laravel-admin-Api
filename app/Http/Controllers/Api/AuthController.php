<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{



    public function register(UserRegisterRequest $request)
    {
        $formData = $request->validated();


        $formData['password'] = bcrypt($request->password);

        $user = User::create($formData);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('admin')->accessToken
        ],200);

    }


    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials))
        {
            $token = Auth::user()->createToken('passportToken')->accessToken;

            $cookie = Cookie('jwt',$token,3600);


            return response()->json([
                'user' => Auth::user(),
                'token' => $token
            ],200)->withCookie($cookie);

        }

        return response()->json([
            'error' => 'Unauthorize'
        ],401);

    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response()->json([
            'message' => 'success',
        ])->withCookie($cookie);
    }
}
