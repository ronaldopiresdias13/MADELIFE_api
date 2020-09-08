<?php

use App\Pessoa;
use App\Endereco;
use App\Homecare;
use App\Paciente;
use App\Tipopessoa;
use App\PessoaEmail;
use App\HomecareEmail;
use App\PessoaEndereco;
use App\PessoaTelefone;
use App\HomecareTelefone;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $homecare->save();
        }

        Schema::drop('homecare_email');
        Schema::drop('homecare_telefone');

        Schema::table('homecares', function (Blueprint $table) {
            $table->dropForeign(['cidade_id']);
            $table->dropColumn(['nome', 'sexo', 'nascimento', 'cpfcnpj', 'rgie', 'endereco', 'cidade_id', 'observacao']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homecares', function (Blueprint $table) {
            $table->string('observacao')->after('paciente_id')->nullable();
            $table->unsignedBigInteger('cidade_id')->nullable()->after('paciente_id');
            $table->foreign('cidade_id')->references('id')->on('cidades')->onDelete('cascade');
            $table->string('endereco')->after('paciente_id')->nullable();
            $table->string('rgie')->after('paciente_id')->nullable();
            $table->string('cpfcnpj')->after('paciente_id')->nullable();
            $table->string('nascimento')->after('paciente_id')->nullable();
            $table->string('sexo')->after('paciente_id')->nullable();
            $table->string('nome')->after('paciente_id')->nullable();
        });

        Schema::create('homecare_email', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homecare_id');
            $table->foreign('homecare_id')->references('id')->on('homecares')->onDelete('cascade');
            $table->unsignedBigInteger('email_id');
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
            $table->string('tipo')->nullable();
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->index('ativo');
            $table->timestamps();
        });

        Schema::create('homecare_telefone', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homecare_id');
            $table->foreign('homecare_id')->references('id')->on('homecares')->onDelete('cascade');
            $table->unsignedBigInteger('telefone_id');
            $table->foreign('telefone_id')->references('id')->on('telefones')->onDelete('cascade');
            $table->string('tipo')->nullable();
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->index('ativo');
            $table->timestamps();
        });

        $homecares = Homecare::all();

        foreach ($homecares as $key => $homecare) {
            $homecare->nome        = $homecare->paciente->pessoa->nome;
            $homecare->sexo        = $homecare->paciente->sexo;
            $homecare->nascimento  = $homecare->paciente->pessoa->nascimento;
            $homecare->cpfcnpj     = $homecare->paciente->pessoa->cpfcnpj;
            $homecare->rgie        = $homecare->paciente->pessoa->rgie;
            $homecare->endereco    = (count($homecare->paciente->pessoa->enderecos) > 0) ? $homecare->paciente->pessoa->enderecos[0]->descricao : null;
            $homecare->cidade_id   = (count($homecare->paciente->pessoa->enderecos) > 0) ? $homecare->paciente->pessoa->enderecos[0]->cidade_id ? $homecare->paciente->pessoa->enderecos[0]->cidade_id : null : null;
            $homecare->observacao  = $homecare->paciente->pessoa->observacao;
            $homecare->update();

            foreach ($homecare->paciente->pessoa->emails as $key => $email) {
                HomecareEmail::create([
                    'homecare_id' => $homecare->id,
                    'email_id' => $email->id,
                    'tipo' => $email->pivot->tipo,
                    'descricao' => $email->pivot->descricao,
                ]);
            }

            foreach ($homecare->paciente->pessoa->telefones as $key => $telefone) {
                HomecareTelefone::create([
                    'homecare_id' => $homecare->id,
                    'telefone_id' => $telefone->id,
                    'tipo' => $telefone->pivot->tipo,
                    'descricao' => $telefone->pivot->descricao,
                ]);
            }

            $id = $homecare->paciente_id;

            $homecare->paciente_id = null;
            $homecare->save();

            Paciente::destroy($id);
        }
    }
}
