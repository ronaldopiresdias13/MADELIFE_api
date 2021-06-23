<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        // Route::prefix('api')
        //     ->middleware('api')
        //     ->namespace($this->namespace)
        //     ->group(base_path('routes/api.php'));
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(function () {
                require base_path('routes/teste.php');

                require base_path('routes/ml/app.php');
                require base_path('routes/ml/auth.php');
                require base_path('routes/ml/mail.php');

                //---------------- WEB ----------------//
                require base_path('routes/ml/web/acaomedicamentos.php');
                require base_path('routes/ml/web/acessos.php');
                require base_path('routes/ml/web/agendamentos.php');
                require base_path('routes/ml/web/bancos.php');
                require base_path('routes/ml/web/beneficios.php');
                require base_path('routes/ml/web/brasindice.php');
                require base_path('routes/ml/web/cargos.php');
                require base_path('routes/ml/web/categoriadocumentos.php');
                require base_path('routes/ml/web/categorianaturezas.php');
                require base_path('routes/ml/web/certificadoprestadores.php');
                require base_path('routes/ml/web/chamados.php');
                require base_path('routes/ml/web/cidades.php');
                require base_path('routes/ml/web/clientes.php');
                require base_path('routes/ml/web/cnabs.php');
                require base_path('routes/ml/web/comentariosmedicao.php');
                require base_path('routes/ml/web/conselhos.php');
                require base_path('routes/ml/web/contas.php');
                require base_path('routes/ml/web/contasbancarias.php');
                require base_path('routes/ml/web/contratos.php');
                require base_path('routes/ml/web/convenios.php');
                require base_path('routes/ml/web/conversas.php');
                require base_path('routes/ml/web/cotacaoproduto.php');
                require base_path('routes/ml/web/cotacoes.php');
                require base_path('routes/ml/web/cuidadoEscalas.php');
                require base_path('routes/ml/web/cuidadoPacientes.php');
                require base_path('routes/ml/web/cuidados.php');
                require base_path('routes/ml/web/dadosbancarios.php');
                require base_path('routes/ml/web/dadoscontratuais.php');
                require base_path('routes/ml/web/diagnosticossecundarios.php');
                require base_path('routes/ml/web/documentos.php');
                require base_path('routes/ml/web/emails.php');
                require base_path('routes/ml/web/empresaPrestador.php');
                require base_path('routes/ml/web/empresas.php');
                require base_path('routes/ml/web/enderecos.php');
                require base_path('routes/ml/web/entradas.php');
                require base_path('routes/ml/web/escalas.php');
                require base_path('routes/ml/web/estoques.php');
                require base_path('routes/ml/web/formacoes.php');
                require base_path('routes/ml/web/fornecedores.php');
                require base_path('routes/ml/web/grupocuidados.php');
                require base_path('routes/ml/web/historicoorcamentos.php');
                require base_path('routes/ml/web/HistoricosRoute.php');
                require base_path('routes/ml/web/homecares.php');
                require base_path('routes/ml/web/horariostrabalho.php');
                require base_path('routes/ml/web/impostos.php');
                require base_path('routes/ml/web/internacoes.php');
                require base_path('routes/ml/web/itensbrasindice.php');
                require base_path('routes/ml/web/marcas.php');
                require base_path('routes/ml/web/medicoes.php');
                require base_path('routes/ml/web/monitoramentoescalas.php');
                require base_path('routes/ml/web/naturezas.php');
                require base_path('routes/ml/web/orcamentocustos.php');
                require base_path('routes/ml/web/orcamentoprodutos.php');
                require base_path('routes/ml/web/orcamentos.php');
                require base_path('routes/ml/web/orcamentoservicos.php');
                require base_path('routes/ml/web/orcs.php');
                require base_path('routes/ml/web/ordemservicoacessos.php');
                require base_path('routes/ml/web/ordemservicoPrestadores.php');
                require base_path('routes/ml/web/ordemservicos.php');
                require base_path('routes/ml/web/ordemservicoServicos.php');
                require base_path('routes/ml/web/outros.php');
                require base_path('routes/ml/web/pacientes.php');
                require base_path('routes/ml/web/pacotes.php');
                require base_path('routes/ml/web/pagamentoexternos.php');
                require base_path('routes/ml/web/pagamentointernos.php');
                require base_path('routes/ml/web/pagamentopessoas.php');
                require base_path('routes/ml/web/pagamentos.php');
                require base_path('routes/ml/web/patrimonios.php');
                require base_path('routes/ml/web/pedidocompras.php');
                require base_path('routes/ml/web/pessoaEmails.php');
                require base_path('routes/ml/web/pessoaEnderecos.php');
                require base_path('routes/ml/web/pessoas.php');
                require base_path('routes/ml/web/pessoaTelefones.php');
                require base_path('routes/ml/web/pils.php');
                require base_path('routes/ml/web/pontos.php');
                require base_path('routes/ml/web/prescricoesbs.php');
                require base_path('routes/ml/web/prestadores.php');
                require base_path('routes/ml/web/prestadorFormacao.php');
                require base_path('routes/ml/web/produtos.php');
                require base_path('routes/ml/web/profissionais.php');
                require base_path('routes/ml/web/relatorioescalas.php');
                require base_path('routes/ml/web/relatorios.php');
                require base_path('routes/ml/web/requisicaoprodutos.php');
                require base_path('routes/ml/web/requisicoes.php');
                require base_path('routes/ml/web/responsaveis.php');
                require base_path('routes/ml/web/saidas.php');
                require base_path('routes/ml/web/salas.php');
                require base_path('routes/ml/web/servicos.php');
                require base_path('routes/ml/web/setores.php');
                require base_path('routes/ml/web/telefones.php');
                require base_path('routes/ml/web/tipopessoas.php');
                require base_path('routes/ml/web/tipoprodutos.php');
                require base_path('routes/ml/web/TissRoute.php');
                require base_path('routes/ml/web/transcricaoprodutos.php');
                require base_path('routes/ml/web/transcricoes.php');
                require base_path('routes/ml/web/unidademedidas.php');
                require base_path('routes/ml/web/UnidadesfederativasRoute.php');
                require base_path('routes/ml/web/userAcessos.php');
                require base_path('routes/ml/web/users.php');
                require base_path('routes/ml/web/vendas.php');

                require base_path('routes/api.php');
            });
    }
}
