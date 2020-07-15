<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexAtivoToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acaomedicamentos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('acessos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('aphs', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('aph_email', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('aph_telefone', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('atribuicoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('bancos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('beneficios', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cargos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('categorianaturezas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cidades', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('clientes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabdetalheas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabdetalhebs', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabheaderarquivoheaderlotes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabheaderarquivos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabheaderarquivotrailerlotes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabheaderlotedetalheas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabheaderlotedetalhebs', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabheaderlotes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabs', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabsantanders', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabtrailerarquivos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cnabtrailerlotes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('conselhos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('contas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('contasbancarias', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('convenios', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cotacao_produto', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cuidados', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cuidado_escalas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('cuidado_grupocuidado', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('custospadroes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('dadoscontratuais', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('diagnosticosecundarios', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('empresas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('empresa_email', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('empresa_endereco', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('empresa_pessoa', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('empresa_prestador', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('empresa_telefone', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('enderecos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('eventos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('evento_email', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('evento_telefone', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('formacoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('grupocuidados', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('historicoorcamentos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('homecares', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('homecare_email', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('homecare_telefone', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('horariomedicamentos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('horariostrabalho', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('impostos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('marcas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('medicoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('naturezas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('orcamentocustos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('outros', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pacientes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('patrimonios', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pessoas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pessoa_email', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pessoa_endereco', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pessoa_outro', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pessoa_telefone', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pils', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('pontos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('prescricoesb', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('prestadores', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('produtos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('profissionais', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('profissional_beneficio', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('profissional_convenio', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('relatorioescalas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('relatorios', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('remocao_email', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('remocao_telefone', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('remocoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('requisicao_produto', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('servicos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('setores', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('telefones', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('tipopessoas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('tipoprodutos', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('transcricao_produto', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('unidademedidas', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->index('ativo');
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->index('ativo');
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
            $table->dropIndex(['ativo']);
        });
        Schema::table('acessos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('aphs', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('aph_email', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('aph_telefone', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('atribuicoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('bancos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('beneficios', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('categorianaturezas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cidades', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabdetalheas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabdetalhebs', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabheaderarquivoheaderlotes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabheaderarquivos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabheaderarquivotrailerlotes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabheaderlotedetalheas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabheaderlotedetalhebs', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabheaderlotes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabs', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabsantanders', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabtrailerarquivos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cnabtrailerlotes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('conselhos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('contas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('contasbancarias', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('convenios', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cotacao_produto', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cuidados', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cuidado_escalas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('cuidado_grupocuidado', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('custospadroes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('dadoscontratuais', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('diagnosticosecundarios', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('empresa_email', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('empresa_endereco', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('empresa_pessoa', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('empresa_prestador', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('empresa_telefone', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('enderecos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('evento_email', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('evento_telefone', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('formacoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('grupocuidados', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('historicoorcamentos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('homecares', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('homecare_email', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('homecare_telefone', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('horariomedicamentos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('horariostrabalho', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('impostos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('marcas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('medicoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('monitoramentoescalas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('naturezas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('orcamentocustos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('orcamento_produto', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('outros', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('patrimonios', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pessoa_email', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pessoa_endereco', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pessoa_outro', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pessoa_telefone', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pils', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('pontos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('prescricoesb', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('prestadores', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('profissional_beneficio', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('profissional_convenio', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('relatorioescalas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('relatorios', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('remocao_email', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('remocao_telefone', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('remocoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('requisicao_produto', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('requisicoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('setores', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('telefones', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('tipopessoas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('tipoprodutos', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('transcricao_produto', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('unidademedidas', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
        Schema::table('user_acesso', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
        });
    }
}
