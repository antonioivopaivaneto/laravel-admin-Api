<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
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

            return response()->json([
                'user' => Auth::user(),
                'token' => $token
            ],200);

        }

        return response()->json([
            'error' => 'Unauthorize'
        ],401);

    }
}
