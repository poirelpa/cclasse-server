<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function current(Request $request){
        return new UserResource(auth()->user());
    }

    public function resetPassword(Request $request){

        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status), 'status' => true], 201)
			: response()->json(['errors' => ['email' => __($status)], 'status' => false], 401);
    }
}
