<?php

namespace App\Http\Controllers\Api\App\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $user = User::firstWhere('email', $request['email']);

        if (!$user) {
            return response()->json([
                'message' => 'Email não cadastrado!'
            ], 404);
        }

        // $credentials = request(['email', 'password']);
        // if (!Auth::attempt($credentials)) {
        //     return response()->json([
        //         'message' => 'Email ou Senha Inválidos!'
        //     ], 401);
        // }

        if (!password_verify($request['password'], $user['password'])) {
            return response()->json([
                'message' => 'Email ou Senha Inválidos!'
            ], 401);
        }

        // if (!Hash::check($request['password'], $user['password'])) {
        //     return response()->json([
        //         'message' => 'Email ou Senha Inválidos!'
        //     ], 401);
        // }

        $tokenResult = $user->createToken('Personal Access Token');
        $token       = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
}
