<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistationRequest;
use App\Services\Auth\AuthServices;

class AuthController extends Controller
{

    public function __construct(private AuthServices $authServices){}

    public function register(RegistationRequest $request)
    {
        return $this->authServices->created($request);
    }

    // Log in function
    public function login(LoginRequest $request)
    {
        return $this->authServices->login($request);

    }
}
