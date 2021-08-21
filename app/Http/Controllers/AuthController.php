<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function current(Request $request){
        return new UserResource(auth()->user());
    }

    public function resetPassword(Request $request){
        if(!empty($request->token)){
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);

            $status = Password::reset(
                $status = $request->only('email', 'password', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );
        } else {
            $request->validate(['email' => 'required|email|exists:users,email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if($status == Password::INVALID_USER){
                // We do not want an attacker to know  which emails are valid or not
                $status = Password::RESET_LINK_SENT;
            }
        }
        return response()->json(['message' => __($status), 'status' => true], 201);
    }
}
