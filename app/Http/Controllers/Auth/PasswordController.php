<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    public function forgetPassword(Request $request)
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
        $token =Str::random(60);
        // insert the token info password_reset token table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        $emailData = [
            'user' => $user,
            'token' => $token
        ];

        Mail::send('emails.password_reset', $emailData, function ($message) use ($user) {
            $message->to($user->email, $user->name);
            $message->subject('Reset password');
        });

        return response()->json(
            [
                'status' => 'success',
                'message' => 'password reset link send to your mail',
                'token'=> $token,
            ],
            Response::HTTP_OK
        );
    }

    // For reset password
    public function passwordReset(Request $request)
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

        $user->update(['password'=> $password]);
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
