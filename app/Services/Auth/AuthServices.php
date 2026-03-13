<?php

namespace App\Services\Auth;

use App\Mail\ResetPasswordLinkMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Pest\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthServices
{
    // For Register user
    public function created($request)
    {
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
    public function login($request)
    {
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


    // forget password 
    public function forgetPassword($request)
    {

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }
        // genarate random token
        $token = Str::random(60);
        // insert the token info password_reset token table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );
        // Get the row data
        $tokenRow = DB::table('password_reset_tokens')->where('email', $email)->first();

        // send mail with a mail class
        Mail::to($user->email)->send(new ResetPasswordLinkMail($user, $tokenRow->token));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'password reset link send to your mail',
                'token' => $tokenRow->token,
            ],
            Response::HTTP_OK
        );
    }


    public function resetPassword($request)
    {
        $email = $request->email;
        $password = $request->password;
        $token = $request->token;

        $tokenRecord = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$tokenRecord || ($token != $tokenRecord->token)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'token invalid'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'user not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if (Carbon::parse($tokenRecord->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'token has expaired'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $user->update(['password' => $password]);
        DB::table('password_reset_tokens')->where('token', $token)->delete();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'password reset successfull'
            ],
            Response::HTTP_OK
        );
    }
}
