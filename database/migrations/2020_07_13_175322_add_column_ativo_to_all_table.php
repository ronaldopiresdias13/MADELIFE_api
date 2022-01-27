<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnAtivoToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acaomedicamentos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('observacao');
        });
        Schema::table('acessos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('nome');
        });
        Schema::table('aphs', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('cidade_id');
        });
        Schema::table('aph_email', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('aph_telefone', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('atribuicoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('valor');
        });
        Schema::table('bancos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('beneficios', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('cargos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('categorianaturezas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('cidades', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('uf');
        });
        Schema::table('clientes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('cnabdetalheas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('ocorrenciasretorno');
        });
        Schema::table('cnabdetalhebs', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('identificacaospb');
        });
        Schema::table('cnabheaderarquivoheaderlotes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('cnabheaderlote_id');
        });
        Schema::table('cnabheaderarquivos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('ocorrenciasretorno');
        });
        Schema::table('cnabheaderarquivotrailerlotes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('cnabtrailerlote_id');
        });
        Schema::table('cnabheaderlotedetalheas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('cnabdetalhea_id');
        });
        Schema::table('cnabheaderlotedetalhebs', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('cnabdetalheb_id');
        });
        Schema::table('cnabheaderlotes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('ocorrenciasretorno');
        });
        Schema::table('cnabs', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('observacao');
        });
        Schema::table('cnabsantanders', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('tipo');
        });
        Schema::table('cnabtrailerarquivos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('filler2');
        });
        Schema::table('cnabtrailerlotes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('ocorrenciasretorno');
        });
        Schema::table('conselhos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('pessoa_id');
        });
        Schema::table('contas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('datavencimento');
        });
        Schema::table('contasbancarias', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('convenios', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('valor');
        });
        Schema::table('cotacao_produto', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('situacao');
        });
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('motivo');
        });
        Schema::table('cuidados', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('cuidado_escalas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('cuidado_grupocuidado', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('grupocuidado_id');
        });
        Schema::table('custospadroes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('tipoconta');
        });
        Schema::table('dadoscontratuais', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('demissao');
        });
        Schema::table('diagnosticosecundarios', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('pil_id');
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('email');
        });
        Schema::table('empresas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('situacao');
        });
        Schema::table('empresa_email', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('email_id');
        });
        Schema::table('empresa_endereco', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('endereco_id');
        });
        Schema::table('empresa_pessoa', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('situacao');
        });
        Schema::table('empresa_prestador', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('empresa_telefone', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('telefone_id');
        });
        Schema::table('enderecos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('tipo');
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('substituto');
        });
        Schema::table('eventos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('cidade_id');
        });
        Schema::table('evento_email', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('email_id');
        });
        Schema::table('evento_telefone', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('telefone_id');
        });
        Schema::table('formacoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('grupocuidados', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('historicoorcamentos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('historico');
        });
        Schema::table('homecares', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('observacao');
        });
        Schema::table('homecare_email', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('homecare_telefone', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('horariomedicamentos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('horario');
        });
        Schema::table('horariostrabalho', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('profissional_id');
        });
        Schema::table('impostos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('marcas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('medicoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('observacao');
        });
        Schema::table('naturezas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('categorianatureza_id');
        });
        Schema::table('orcamentocustos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('valortotal');
        });
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('valorcustomensal');
        });
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('inss');
        });
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('profissional_id');
        });
        Schema::table('outros', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('valor');
        });
        Schema::table('pacientes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('responsavel_id');
        });
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('situacao');
        });
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('anexo');
        });
        Schema::table('patrimonios', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('pessoas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('pessoa_email', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('pessoa_endereco', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('endereco_id');
        });
        Schema::table('pessoa_outro', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('outro_id');
        });
        Schema::table('pessoa_telefone', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('pils', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('evolucao');
        });
        Schema::table('pontos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('prescricoesb', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('pil_id');
        });
        Schema::table('prestadores', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('certificado');
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('formacao_id');
        });
        Schema::table('produtos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('tipo');
        });
        Schema::table('profissionais', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('dadoscontratuais_id');
        });
        Schema::table('profissional_beneficio', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('beneficio_id');
        });
        Schema::table('profissional_convenio', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('convenio_id');
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('formacao_id');
        });
        Schema::table('relatorioescalas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('nome');
        });
        Schema::table('relatorios', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('texto');
        });
        Schema::table('remocao_email', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('remocao_telefone', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('descricao');
        });
        Schema::table('remocoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('observacao');
        });
        Schema::table('requisicao_produto', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('parentesco');
        });
        Schema::table('servicos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('status');
        });
        Schema::table('setores', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('telefones', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('telefone');
        });
        Schema::table('tipopessoas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('pessoa_id');
        });
        Schema::table('tipoprodutos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('transcricao_produto', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('observacao');
        });
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('ordemservico_id');
        });
        Schema::table('unidademedidas', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('empresa_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('remember_token');
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('acesso_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acaomedicamentos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('acessos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('aphs', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('aph_email', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('aph_telefone', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('atribuicoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('bancos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('beneficios', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('categorianaturezas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cidades', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabdetalheas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabdetalhebs', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabheaderarquivoheaderlotes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabheaderarquivos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabheaderarquivotrailerlotes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabheaderlotedetalheas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabheaderlotedetalhebs', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabheaderlotes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabs', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabsantanders', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabtrailerarquivos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cnabtrailerlotes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('conselhos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('contas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('contasbancarias', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('convenios', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cotacao_produto', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cuidados', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cuidado_escalas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('cuidado_grupocuidado', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('custospadroes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('dadoscontratuais', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('diagnosticosecundarios', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('empresa_email', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('empresa_endereco', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('empresa_pessoa', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('empresa_prestador', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('empresa_telefone', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('enderecos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('evento_email', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('evento_telefone', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('formacoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('grupocuidados', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('historicoorcamentos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('homecares', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('homecare_email', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('homecare_telefone', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('horariomedicamentos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('horariostrabalho', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('impostos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('marcas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('medicoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('naturezas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('orcamentocustos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('outros', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('patrimonios', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pessoa_email', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pessoa_endereco', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pessoa_outro', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pessoa_telefone', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pils', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('pontos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('prescricoesb', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('prestadores', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('profissional_beneficio', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('profissional_convenio', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('relatorioescalas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('relatorios', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('remocao_email', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('remocao_telefone', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('remocoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('requisicao_produto', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('setores', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('telefones', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('tipopessoas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('tipoprodutos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('transcricao_produto', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('unidademedidas', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
}
