<?php

namespace App\Services\Auth;

use App\Models\User;

class AuthServices
{   
    // For Register user
    public function created($request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        $token = auth()->login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expaired_in' => auth()->factory()->getTTL() * 60
            ],
        ]);
    }


    // For Login user
    public function login($request){
                $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'data' => [
                'user' => auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expired_in' => auth()->factory()->getTTL() * 60
            ],
        ]);
    }


}
