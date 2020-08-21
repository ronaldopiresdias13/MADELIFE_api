<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Email;
use App\Pessoa;
use App\Conselho;
use App\Prestador;
use Carbon\Carbon;
use App\PessoaEmail;
use App\PrestadorFormacao;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Tipopessoa;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // // $request->validate([
        // //     'email'       => 'string|email',
        // //     'password'    => 'required|string',
        // //     'remember_me' => 'boolean'
        // // ]);

        // // $user = User::firstWhere('email', $request['email']);

        // // $user->acessos;
        // // $user->pessoa;
        // // // if ($user->pessoa['tipo'] == 'Prestador') {
        // // $user->pessoa->prestador;
        // // // }
        // // // if ($user->pessoa['tipo'] == 'Cliente') {
        // // $user->pessoa->cliente;
        // // // }
        // // // if ($user->pessoa['tipo'] == 'Profissional') {
        // // $user->pessoa->profissional;
        // // // }



        // $user = User::with([
        //     'acessos',
        //     'pessoa' => function (BelongsTo $query) {
        //         $query->with(['prestador']);
        //         $query->with(['cliente']);
        //         $query->with(['profissional']);
        //     }
        // ])
        //     ->where('email', $request['email'])
        //     ->first();

        // return $user;








        // if (!Hash::check($request['password'], $user->password)) {
        //     return response()->json([
        //         'message' => 'Email ou Senha Inválidos!'
        //     ], 401);
        // }

        // // if ($user && password_verify($request->password, $user->password)) {
        // //     // authenticated user,
        // //     // do something...
        // // }

        // // $user->acessos;
        // // $user->pessoa;
        // // // if ($user->pessoa['tipo'] == 'Prestador') {
        // // $user->pessoa->prestador;
        // // // }
        // // // if ($user->pessoa['tipo'] == 'Cliente') {
        // // $user->pessoa->cliente;
        // // // }
        // // // if ($user->pessoa['tipo'] == 'Profissional') {
        // // $user->pessoa->profissional;
        // // // }



        // // return $user;





        // $tokenResult = $user->createToken('Personal Access Token');
        // $token       = $tokenResult->token;
        // if ($request->remember_me) {
        //     $token->expires_at = Carbon::now()->addWeeks(1);
        // }
        // $token->save();

        // return response()->json([
        //     'access_token' => $tokenResult->accessToken,
        //     'token_type'   => 'Bearer',
        //     'expires_at'   => Carbon::parse(
        //         $tokenResult->token->expires_at
        //     )->toDateTimeString(),
        //     'user' => $user
        // ]);





        $request->validate([
            // 'cpfcnpj'  => 'string',
            'email'       => 'string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou Senha Inválidos!'
            ], 401);
        }
        $user = $request->user();
        $user->acessos;
        $user->pessoa;
        // if ($user->pessoa['tipo'] == 'Prestador') {
        $user->pessoa->prestador;
        // }
        // if ($user->pessoa['tipo'] == 'Cliente') {
        $user->pessoa->cliente;
        // }
        // if ($user->pessoa['tipo'] == 'Profissional') {
        $user->pessoa->profissional;
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
            )->toDateTimeString(),
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $cpfcnpj = User::firstWhere('cpfcnpj', $request['cpfcnpj']);

        $email = User::firstWhere('email', $request['user']['email']);

        $user = null;

        if ($cpfcnpj) {
            $user = $cpfcnpj;
        } elseif ($email) {
            $user = $email;
        }

        if ($user) {
            $prestador = Prestador::firstWhere('pessoa_id', $user->pessoa->id);
            if ($prestador) {
                return response()->json('Você já possui cadastro!', 400)->header('Content-Type', 'text/plain');
            } else {
                DB::transaction(function () use ($request, $user) {
                    $pessoa_email = PessoaEmail::firstOrCreate([
                        'pessoa_id' => $user->pessoa_id,
                        'email_id'  => Email::firstOrCreate(
                            [
                                'email' => $user->email,
                            ]
                        )->id,
                        'tipo'      => 'Pessoal',
                    ]);

                    $conselho = Conselho::create(
                        [
                            'instituicao' => $request['conselho']['instituicao'],
                            'numero'      => $request['conselho']['numero'],
                            'pessoa_id'   => $user->pessoa_id
                        ]
                    );

                    $formacao = PrestadorFormacao::create(
                        [
                            'prestador_id' => Prestador::create(
                                [
                                    'pessoa_id' => $user->pessoa_id,
                                    'sexo'      => $request['prestador']['sexo']
                                ]
                            )->id,
                            'formacao_id'  => $request['prestador']['formacao_id']
                        ]
                    );
                });
            }
        } else {
            DB::transaction(function () use ($request) {
                $user = User::create(
                    [
                        'cpfcnpj'    => $request['cpfcnpj'],
                        'email'      => $request['user']['email'],
                        'password'   =>  bcrypt($request['user']['password']),
                        'pessoa_id'  => Pessoa::create(
                            [
                                'nome'       => $request['nome'],
                                // 'nascimento' => $request['nascimento'],
                                'cpfcnpj'    => $request['cpfcnpj'],
                                'status'     => $request['status']
                            ]
                        )->id
                    ]
                );
                $tipopessoa = Tipopessoa::create([
                    'tipo'      => 'Prestador',
                    'pessoa_id' => $user->pessoa_id,
                    'ativo'     => 1
                ]);
                $pessoa_email = PessoaEmail::firstOrCreate([
                    'pessoa_id' => $user->pessoa_id,
                    'email_id'  => Email::firstOrCreate(
                        [
                            'email' => $user->email,
                        ]
                    )->id,
                    'tipo'      => 'Pessoal',
                ]);

                $conselho = Conselho::create(
                    [
                        'instituicao' => $request['conselho']['instituicao'],
                        'numero'      => $request['conselho']['numero'],
                        'pessoa_id'   => $user->pessoa_id
                    ]
                );

                $formacao = PrestadorFormacao::create(
                    [
                        'prestador_id' => Prestador::create(
                            [
                                'pessoa_id' => $user->pessoa_id,
                                'sexo'      => $request['prestador']['sexo']
                            ]
                        )->id,
                        'formacao_id'  => $request['prestador']['formacao_id']
                    ]
                );
            });
        }
    }

    public function reset(Request $request)
    {
        $user = User::firstWhere(
            ['email' => $request->email]
        );

        if ($user == null) {
            return response()->json([
                'message' => 'Email não cadastrado!'
            ], 404);
        }
        $senha = Str::random(8);
        $user->password = bcrypt($senha);
        $user->save();
        Mail::send(new ResetPassword($user, $senha));
    }

    public function change(Request $request)
    {
        $request->validate([
            'email'       => 'string|email',
            'password'    => 'required|string',
            'newPassword' => 'required|string'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou Senha Inválidos!'
            ], 401);
        }
        $user        = $request->user();
        $user->password = bcrypt($request->newPassword);
        $user->save();
    }

    public function logout(Request $request)
    {
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
