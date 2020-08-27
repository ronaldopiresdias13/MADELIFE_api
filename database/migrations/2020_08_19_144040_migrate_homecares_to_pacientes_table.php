<?php

use App\Endereco;
use App\Homecare;
use App\Paciente;
use App\Pessoa;
use App\PessoaEmail;
use App\PessoaEndereco;
use App\PessoaTelefone;
use App\Tipopessoa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateHomecaresToPacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $homecares = Homecare::all();
        foreach ($homecares as $key => $homecare) {
            $paciente = Paciente::create([
                'empresa_id'     => 1,
                'pessoa_id'      => Pessoa::create([
                    'nome'        => $homecare->nome,
                    'nascimento'  => $homecare->nascimento,
                    'cpfcnpj'     => $homecare->cpfcnpj,
                    'rgie'        => $homecare->rgie,
                    'observacoes' => $homecare->observacao,
                    'perfil'      => "",
                    'status'      => 1,
                ])->id,
                'responsavel_id' => null,
                'sexo'           => $homecare->sexo,
                'ativo'          => 1
            ]);
            Tipopessoa::firstOrCreate([
                'tipo'      => "Paciente",
                'pessoa_id' => $paciente->pessoa_id
            ]);
            if ($homecare->endereco) {
                $pessoa_endereco = PessoaEndereco::create([
                    'pessoa_id'   => $paciente->pessoa_id,
                    'endereco_id' => Endereco::firstOrCreate(
                        [
                            'cep'         => "",
                            'cidade_id'   => $homecare->cidade_id,
                            'rua'         => "",
                            'bairro'      => "",
                            'numero'      => "",
                            'complemento' => "",
                            'tipo'        => "",
                            'descricao'   => $homecare->endereco,
                        ]
                    )->id,
                ]);
            }

            foreach ($homecare->emails as $key => $email) {
                $pessoa_email = PessoaEmail::create([
                    'pessoa_id' => $paciente->pessoa_id,
                    'email_id'  => $email->id,
                    'tipo'      => $email->pivot->tipo,
                    'descricao' => $email->pivot->descricao,
                    'ativo'     => 1
                ]);
            }
            foreach ($homecare->telefones as $key => $telefone) {
                $pessoa_telefone = PessoaTelefone::create([
                    'pessoa_id'   => $paciente->pessoa_id,
                    'telefone_id' => $telefone->id,
                    'tipo'        => $telefone->pivot->tipo,
                    'descricao'   => $telefone->pivot->descricao,
                    'ativo'       => 1
                ]);
            }
            $homecare->paciente_id = $paciente->id;
            $homecare->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            //
        });
    }
}
