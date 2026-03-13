<?php

namespace App\Http\Requests\Auth;


class ForgetPasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email'
        ];
    }
}
