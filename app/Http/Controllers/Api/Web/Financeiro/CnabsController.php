<?php

namespace App\Http\Controllers\Api\Web\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CnabRequest;
use App\Http\Resources\CnabResource;
use App\Models\Pagamentopessoa;
use App\Models\RegistroCnab;
use App\Models\Tipopessoa;
use App\Services\CnabService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CnabsController extends Controller
{
    public function getCategorias()
    {
        $categorias = Tipopessoa::distinct('tipo')->pluck('tipo');

        return $categorias;
    }


    public function getCnabs(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $cnabs = RegistroCnab::where('empresa_id', '=', $empresa_id)->orderBy('created_at', 'desc')->get();

        return response()->json(['cnabs' => CnabResource::collection($cnabs)]);
    }


    public function gerarCnab(CnabRequest $request)
    {
        // return $request;
        $data = $request->validated();

        $user = $request->user();

        $cnabService = new CnabService($data['banco'], $data['dados'], $data['mes'], $data['observacao'], $data['data'], $user);
        $resposta = $cnabService->criar_cnab();
        // $registro = RegistroCnab::find($resposta['cnab']);
        if ($resposta['status'] == true) {
            return $resposta;
        } else {
            Log::error('Erro ao gerar o Cnab. Verifique se todos os dados estão corretos. Motivo: ' . $resposta['message']);
            throw ValidationException::withMessages([
                'cnab' => ['Erro ao gerar o Cnab. Verifique se todos os dados estão corretos. Motivo: ' . $resposta['message']],
            ]);
        }
    }

    public function downloadCnab($id)
    {
        $registro = RegistroCnab::find($id);
        $file = public_path() . '/' . $registro->arquivo;
        $name = explode('/', $registro->arquivo)[count(explode('/', $registro->arquivo)) - 1];
        Log::info($name);
        $headers = [
            'Content-type'        => 'text/txt',
        ];
        return response()->download($file, $name, $headers);
    }


    public function mudarSituacao(CnabRequest $request)
    {
        $data = $request->validated();
        $registro = RegistroCnab::find($data['cnab_id']);
        $user = $request->user();
        $pessoa = $user->pessoa;
        $profissinal = $pessoa->profissional()->first();
        // $user = $request->user();
        $pessoas_ids = [];
        if ($data['situacao'] == 'Pago') {
            if (isset($data['ids'])) {
                $registro->pagamentos()->whereIn('cnabpessoas.id', $data['ids'])->update(['status' => 'P']);
                $registro->pagamentos()->whereNotIn('cnabpessoas.id', $data['ids'])->update(['status' => 'N']);


                $pessoas_ids = $registro->pagamentos()->whereIn('cnabpessoas.id', $data['ids'])->pluck('cnabpessoas.pessoa_id');
                $pessoas_ids_total = $registro->pagamentos()->pluck('cnabpessoas.pessoa_id');

                Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                    ->where('empresa_id', $profissinal->empresa_id)
                    ->whereIn('pessoa_id', $pessoas_ids)
                    ->whereIn('pessoa_id', $pessoas_ids_total)
                    ->where('situacao', 'Aprovado')
                    // ->where('status', false)
                    ->where(DB::raw("date_format(str_to_date(pagamentopessoas.periodo1, '%Y-%m-%d'), '%Y-%m')"), "=", $registro->mes)
                    ->update(['status' => true]);

                Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                    ->where('empresa_id', $profissinal->empresa_id)
                    ->whereNotIn('pessoa_id', $pessoas_ids)
                    ->where('situacao', 'Aprovado')
                    ->whereIn('pessoa_id', $pessoas_ids_total)

                    // ->where('status', false)
                    ->where(DB::raw("date_format(str_to_date(pagamentopessoas.periodo1, '%Y-%m-%d'), '%Y-%m')"), "=", $registro->mes)
                    ->update(['status' => false]);
            }
        }

        $registro->fill($data)->save();

        return response()->json(['status' => true, 'pessoas_ids' => $pessoas_ids]);
    }
}
