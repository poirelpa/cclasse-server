<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function register(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|min:3',
            'password' => 'required|min:8'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json(['message' => "Compte créé.", 'status' => true, 'user' => $user], 201);

    }
}
