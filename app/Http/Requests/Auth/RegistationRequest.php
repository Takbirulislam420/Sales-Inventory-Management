<?php

namespace App\Http\Requests\Auth;


class RegistationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'

        ];
    }
}
