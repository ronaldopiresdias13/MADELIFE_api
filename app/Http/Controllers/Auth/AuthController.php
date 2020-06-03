<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            // 'cpfcnpj'  => 'string',
            'email'       => 'string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Email ou Senha Inválidos!'
            ], 401);
        $user        = $request->user();
        $user->acessos;
        $user->pessoa;
        $tokenResult = $user->createToken('Personal Access Token');
        $token       = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'cpfcnpj'  => 'string|unique:users',
            'email'    => 'string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = new User;
        $user->cpfcnpj  =        $request->cpfcnpj  ;
        $user->email    =        $request->email    ;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Usuario criado com Sucesso!'
        ], 201);
    }

    public function reset(Request $request)
    {
        $user = User::firstWhere(
            ['email' => $request->email]
        );
        // dd($user);
        $senha = str_random(8);
        $user->senha = bcrypt($senha);
        $user->save();
        // return new \App\Mail\ResetPassword($user, $senha);
        Mail::send(new \App\Mail\ResetPassword($user, $senha));

        // $request->validate([
        //     'email'    => 'string|email|unique:users',
        //     'password' => 'required|string'
        // ]);

        // User::updateWhere(
        //     ['email'    => $request->email],
        //     ['password' => bcrypt($request->password)]
        // );

        // return response()->json([
        //     'message' => 'Senha alterada com Sucesso!'
        // ], 201);
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Desconectado com Sucesso!'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $user->pessoa;
        return response()->json($user);
        // return response()->json($request->user());
    }
}
