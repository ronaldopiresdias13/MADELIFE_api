<?php

namespace App\Http\Controllers\Web\Itemtabelaprecos;

use App\Http\Controllers\Controller;
use App\Models\Itemtabelapreco;
use App\Models\Tabelapreco;
use App\Models\Versaotabelapreco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemtabelaprecosController extends Controller
{
    protected $empresa_id;
    protected $tabela;
    protected $versao;
    protected $file;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Itemtabelapreco::where('tabela_id', $request->versao_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $this->empresa_id = $empresa_id;
        $this->tabela = $request->tabela['nome'];
        $this->versao = $request->versao;
        $this->file   = $request->file;

        $func = $this->tabela;
        return $this->$func();
        // foreach ($request->itenstabelapreco as $key => $item) {
        //     $itemtabelapreco = new Itemtabelapreco();
        //     $itemtabelapreco->versaotabelapreco_id = $item['versaotabelapreco_id'];
        //     $itemtabelapreco->codigo               = $item['codigo'];
        //     $itemtabelapreco->tiss                 = $item['tiss'];
        //     $itemtabelapreco->tuss                 = $item['tuss'];
        //     $itemtabelapreco->nome                 = $item['nome'];
        //     $itemtabelapreco->preco                = $item['preco'];
        //     $itemtabelapreco->save();
        // }

        // return $itemtabelapreco;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Itemtabelapreco  $itemtabelapreco
     * @return \Illuminate\Http\Response
     */
    public function show(Itemtabelapreco $itemtabelapreco)
    {
        return $itemtabelapreco;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Itemtabelapreco  $itemtabelapreco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Itemtabelapreco $itemtabelapreco)
    {
        $itemtabelapreco->versaotabelapreco_id = $request->versaotabelapreco_id;
        $itemtabelapreco->codigo               = $request->codigo;
        $itemtabelapreco->tiss                 = $request->tiss;
        $itemtabelapreco->tuss                 = $request->tuss;
        $itemtabelapreco->nome                 = $request->nome;
        $itemtabelapreco->preco                = $request->preco;
        $itemtabelapreco->save();
        return $itemtabelapreco;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Itemtabelapreco  $itemtabelapreco
     * @return \Illuminate\Http\Response
     */
    public function destroy(Itemtabelapreco $itemtabelapreco)
    {
        $itemtabelapreco->delete;
    }

    private function brasindice()
    {
        $tabela = Tabelapreco::firstOrCreate([
            'nome'       => $this->tabela,
            'empresa_id' => $this->empresa_id
        ]);

        $versao = Versaotabelapreco::firstOrCreate([
            'versao'         => $this->versao,
            'tabelapreco_id' => $tabela->id
        ]);

        $md5 = md5_file($this->file);
        $caminho = 'temp/';
        $nome = $md5 . '.' . explode(';', explode('/', $this->file)[1])[0];
        $file = explode(',', $this->file)[1];
        Storage::put($caminho . $nome, base64_decode($file));

        $file = fopen(storage_path("app/public/temp/") . $nome, "r");

        while (!feof($file)) {
            $linha = fgets($file);
            $array = explode('","', $linha);
            foreach ($array as $key => $item) {
                $array[$key] = str_replace(['"', "\r", "\n"], "", $item);
            }

            if ($array[0]) {
                Itemtabelapreco::updateOrCreate(
                    [
                        'versaotabelapreco_id' => $versao->id,
                        'codigo'               => $array[0] . '.' . $array[2] . '.' . $array[4] // 18
                    ],
                    [
                        'tiss'  => '',
                        'tuss'  => '',
                        // 'nome'  => utf8_encode($array[3]) . ' ' . $array[5], // 2
                        'nome'  => utf8_encode($array[3]) . ' ' . $array[5], // 2
                        'preco' => $array[9] //
                    ]
                );
            }
        }

        fclose($file);
        Storage::delete([$caminho . $nome]);

        return 'Importado Brasindice!!!';
    }

    private function simpro()
    {
        $tabela = Tabelapreco::firstOrCreate([
            'nome'       => $this->tabela,
            'empresa_id' => $this->empresa_id
        ]);

        $versao = Versaotabelapreco::firstOrCreate([
            'versao'         => $this->versao,
            'tabelapreco_id' => $tabela->id
        ]);

        $md5 = md5_file($this->file);
        $caminho = 'temp/';
        $nome = $md5 . '.' . explode(';', explode('/', $this->file)[1])[0];
        $file = explode(',', $this->file)[1];
        Storage::put($caminho . $nome, base64_decode($file));

        $file = fopen(storage_path("app/public/temp/") . $nome, "r");

        while (!feof($file)) {
            $linha = fgets($file);

            $codigo = substr($linha, 267, 10);
            $name = substr($linha, 32, 100);
            $preco = substr($linha, 155, 8) . '.' . substr($linha, 163, 2);

            if ($linha) {
                Itemtabelapreco::updateOrCreate(
                    [
                        'versaotabelapreco_id' => $versao->id,
                        'codigo'               => $codigo // $array[18]
                    ],
                    [
                        'tiss'  => '',
                        'tuss'  => '',
                        'nome'  => utf8_encode($name), // $array[2]),
                        'preco' => $preco // $array[6]
                    ]
                );
            }
        }

        fclose($file);
        Storage::delete([$caminho . $nome]);

        return 'Importado Simpro!!!';
    }
}
