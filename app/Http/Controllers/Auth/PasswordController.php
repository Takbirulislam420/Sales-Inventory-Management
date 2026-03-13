<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\AuthServices;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function __construct(private AuthServices $authServices) {}
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        return $this->authServices->forgetPassword($request);
    }

    // For reset password
    public function passwordReset(ResetPasswordRequest $request)
    {
        return $this->authServices->resetPassword($request);
    }
}
