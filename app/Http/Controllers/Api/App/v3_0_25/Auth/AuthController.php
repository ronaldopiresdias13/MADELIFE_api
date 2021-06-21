<?php

namespace App\Http\Controllers\Api\App\v3_0_25\Auth;

use App\Models\User;
use App\Models\Email;
use App\Models\Pessoa;
use App\Models\Conselho;
use App\Models\Prestador;
use Carbon\Carbon;
use App\Models\Tipopessoa;
use App\Models\PessoaEmail;
use App\Models\PrestadorFormacao;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Endereco;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Telefone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::firstWhere('email', $request['email']);

        if (!$user || !$user->pessoa->prestador) {
            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text' => 'Email não cadastrado!'
                ]
            ], 400)
                ->header('Content-Type', 'application/json');
        }

        if (!password_verify($request['password'], $user['password'])) {
            // return response()->json([
            //     'message' => 'E-mail e/ou Senha incorretos.'
            // ], 404);

            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text' => 'Email ou senha incorretos!'
                ]
            ], 400)
                ->header('Content-Type', 'application/json');
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token       = $tokenResult->token;
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function reset(Request $request)
    {
        $user = User::firstWhere(
            ['email' => $request->email]
        );

        if ($user == null) {
            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text' => 'Email não cadastrado!'
                ]
            ], 202)
                ->header('Content-Type', 'application/json');
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $senha = '';
        for ($i = 0; $i < 8; $i++) {
            $senha .= $characters[rand(0, $charactersLength - 1)];
        }

        $user->password = bcrypt($senha);
        $user->save();
        Mail::send(new ResetPassword($user, $senha));

        return response()->json([
            'alert' => [
                'title' => 'Parabéns!',
                'text' => 'Se informou os dados corretos, você receberá um email contendo uma nova senha para acessar o aplicativo!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    public function change(Request $request)
    {
        $user = $request->user();

        if (!password_verify($request['password'], $user['password'])) {
            return response()->json([
                'message' => 'Senha atual inválida!'
            ], 0);
        }

        $user->password = bcrypt($request->newPassword);
        $user->save();
    }

    public function register(Request $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create(
                [
                    'cpfcnpj'    => $request['cpfcnpj'],
                    'email'      => $request['email'],
                    'password'   =>  bcrypt($request['user']['password']),
                    'pessoa_id'  => Pessoa::create(
                        [
                            'nome'       => $request['infopessoal']['nome'],
                            'nascimento' => $request['infopessoal']['nascimento'],
                            'rgie'       => $request['infopessoal']['rgie'],
                            'cpfcnpj'    => $request['cpfcnpj'],
                            'status'     => $request['status']
                        ]
                    )->id
                ]
            );
            Tipopessoa::create([
                'tipo'      => 'Prestador',
                'pessoa_id' => $user->pessoa_id,
                'ativo'     => 1
            ]);
            PessoaEmail::firstOrCreate([
                'pessoa_id' => $user->pessoa_id,
                'email_id'  => Email::firstOrCreate(
                    [
                        'email' => $user->email,
                    ]
                )->id,
                'tipo'      => 'Pessoal',
            ]);
            PessoaTelefone::firstOrCreate([
                'pessoa_id' => $user->pessoa_id,
                'telefone_id'  => Telefone::firstOrCreate(
                    [
                        'telefone' => $request['infotelefone']['telefone'],
                    ]
                )->id,
                'tipo'      => 'Pessoal',
            ]);

            if ($request['infoprofissional']['numeroconselho']) {
                Conselho::create(
                    [
                        'instituicao' => $request['infoprofissional']['formacao']['conselho'],
                        'numero'      => $request['infoprofissional']['numeroconselho'],
                        'pessoa_id'   => $user->pessoa_id
                    ]
                );
            }


            PrestadorFormacao::create(
                [
                    'prestador_id' => Prestador::create(
                        [
                            'pessoa_id' => $user->pessoa_id,
                            'sexo'      => $request['infopessoal']['sexo']
                        ]
                    )->id,
                    'formacao_id'  => $request['infoprofissional']['formacao']['id']
                ]
            );

            PessoaEndereco::create([
                'pessoa_id' => $user->pessoa_id,
                'endereco_id' => Endereco::create(
                    [
                        'descricao'   => "",
                        'cep'         => $request['infoendereco']['cep'],
                        'cidade_id'   => $request['infoendereco']['cidade']['id'],
                        'rua'         => $request['infoendereco']['rua'],
                        'bairro'      => $request['infoendereco']['bairro'],
                        'numero'      => $request['infoendereco']['numero'],
                        'complemento' => $request['infoendereco']['complemento'],
                        'tipo'        => 'Residencial',
                        'ativo'       => true
                    ]
                )->id,
                'ativo' => true
            ]);
        });
        // }
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }

    public function verificaCpfEmail(Request $request)
    {
        if (!$request->cpfcnpj && !$request->email) {
            return response()->json([
                'alert' => [
                    'title' => 'Atenção!',
                    'text' => 'Por favor informe um CPF ou Email!'
                ]
            ], 200)
                ->header('Content-Type', 'application/json');
        }

        if ($request->cpfcnpj) {
            $user = User::where('cpfcnpj', $request->cpfcnpj)
                ->first();

            if ($user) {
                return response()->json([
                    'alert' => [
                        'title' => 'Ops!',
                        'text' => 'Você já possui um cadastro com esse CPF!'
                    ]
                ], 200)
                    ->header('Content-Type', 'application/json');
            }
        }

        if ($request->email) {
            $user = User::where('email', $request->email)
                ->first();

            if ($user) {
                return response()->json([
                    'alert' => [
                        'title' => 'Ops!',
                        'text' => 'Você já possui um cadastro com esse E-mail!'
                    ]
                ], 200)
                    ->header('Content-Type', 'application/json');
            }
        }

        return null;
    }
}
