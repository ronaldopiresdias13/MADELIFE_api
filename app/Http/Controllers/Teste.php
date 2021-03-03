<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $dompdf = new Dompdf();
        // $dompdf->loadHtml("<h1>Olá Mundo!</h1><p>Esse é meu primeiro PDF!</p>");

        $array = [
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ],
            [
                'name' => 'Lucas',
            ]
        ];

        // return view('teste', ['itens' => $array]);

        // $dompdf = new Dompdf(["enable_remote" => true]);
        $dompdf->loadHtml(view('teste', ['itens' => $array]));

        // ob_start();
        // require __DIR__. "../../../../resources/views/teste.php";
        // $dompdf->loadHtml(ob_get_clean());

        $dompdf->setPaper("A4");

        $dompdf->render();

        // $dompdf->stream("file.pdf");
        $dompdf->stream("file.pdf", ["Attachment" => false]);

        // var_dump($dompdf->output());
        // return 'Hello World!';
        // return view('teste');

        // return $request;

        // $hoje = getdate();
        // $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);

        // $datainicio = $request['datainicio'] ? $request['datainicio'] : date("Y-m-01", strtotime($data));
        // $datafim    = $request['datafim']    ? $request['datafim']    : date("Y-m-t", strtotime($data));

        // return $datainicio;
        // return $datafim;


        // $hoje = getdate();
        // $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);

        // return 'First day : '. date("Y-m-01", strtotime($data)).' - Last day : '. date("Y-m-t", strtotime($data));

        // return $data;

        // return response()->json([
        //     'success' => [
        //         'text' => 'Salvo com sucesso!',
        //         'duration' => 2000
        //     ]
        // ], 200)
        //     ->header('Content-Type', 'application/json');

        // return response()->json([
        //     'error' => [
        //         'text' => 'Erro ao salvar!'
        //     ]
        // ], 400)
        //     ->header('Content-Type', 'application/json');

        // return "Teste Back";
        // // DB::select('select * from users where active = ?', [1])

        // $hoje = getdate();
        // // $data = $hoje['year'] . '-' . (($hoje['mon'] - 11) < 10 ? '0' . ($hoje['mon'] - 11) : $hoje['mon']) . '-' . $hoje['mday'];
        // $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        // return DB::select(
        //     'SELECT p.descricao, pes.nome, e.dataentrada FROM transcricao_produto tp
        //     INNER JOIN produtos p ON p.id = tp.produto_id
        //     INNER JOIN transcricoes t ON t.id = tp.transcricao_id
        //     INNER JOIN ordemservicos os ON os.id = t.ordemservico_id
        //     INNER JOIN orcamentos o ON o.id = os.orcamento_id
        //     INNER JOIN homecares h ON h.orcamento_id = o.id
        //     INNER JOIN pacientes pac ON pac.id = h.paciente_id
        //     INNER JOIN pessoas pes ON pes.id = pac.pessoa_id
        //     INNER JOIN escalas e ON e.ordemservico_id = os.id
        //     WHERE NOT EXISTS (SELECT * FROM acaomedicamentos am WHERE tp.id = am.transcricao_produto_id)
        //     AND os.id LIKE ?
        //     AND e.dataentrada BETWEEN ? AND ?',
        //     [
        //         $request['os'] ? $request['os'] : '%',
        //         $request['dataIni'] ? $request['dataIni'] : $data,
        //         $request['dataFim'] ? $request['dataFim'] : $data
        //     ]
        // );
        // $prestadores = Prestador::with([
        //     'pessoa:id,nome',
        //     'formacoes:formacoes.id,descricao',
        //     'pessoa.conselhos:conselhos.id,instituicao,numero,uf,pessoa_id',
        //     'pessoa.enderecos.cidade',
        //     'pessoa.telefones:telefones.id,telefone'
        // ])
        //     ->get(['id', 'pessoa_id']);

        // $result = [];

        // foreach ($prestadores as $key => $prestador) {
        //     $inserir = true;

        //     if ($inserir && $request['nome']) {
        //         if (str_contains(strtolower($prestador->pessoa->nome), strtolower($request['nome']))) {
        //             $inserir = true;
        //         } else {
        //             $inserir = false;
        //         }
        //     }
        //     if ($inserir && $request['formacao']) {
        //         $contain = false;
        //         foreach ($prestador->formacoes as $key => $formacao) {
        //             if (str_contains(strtolower($formacao->descricao), strtolower($request['formacao']))) {
        //                 $contain = true;
        //             }
        //         }
        //         if ($contain) {
        //             $inserir = true;
        //         } else {
        //             $inserir = false;
        //         }
        //     }
        //     if ($inserir && $request['cidade']) {
        //         if ($prestador->pessoa->enderecos) {
        //             $contain = false;
        //             foreach ($prestador->pessoa->enderecos as $key => $endereco) {
        //                 if ($endereco->cidade) {
        //                     if (str_contains(strtolower($endereco->cidade->nome), strtolower($request['cidade']))) {
        //                         $contain = true;
        //                     }
        //                 }
        //             }
        //             if ($contain) {
        //                 $inserir = true;
        //             } else {
        //                 $inserir = false;
        //             }
        //         } else {
        //             $inserir = false;
        //         }
        //     }

        //     if ($inserir && ($request['nome'] || $request['formacao'] || $request['cidade'])) {
        //         array_push($result, $prestador);
        //     }
        // }

        // return $result;
    }
}
