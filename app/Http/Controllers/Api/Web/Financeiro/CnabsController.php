<?php

namespace App\Http\Controllers\Api\Web\Financeiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\CnabRequest;
use App\Http\Resources\CnabResource;
use App\Models\RegistroCnab;
use App\Models\Tipopessoa;
use App\Services\CnabService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CnabsController extends Controller
{
    public function getCategorias(){
        $categorias = Tipopessoa::distinct('tipo')->pluck('tipo');

        return $categorias;
    }


    public function getCnabs(Request $request){
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $cnabs = RegistroCnab::where('empresa_id','=',$empresa_id)->orderBy('created_at','desc')->get();

        return response()->json(['cnabs'=>CnabResource::collection($cnabs)]);
    }


    public function gerarCnab(CnabRequest $request){
        $data=$request->validated();

        $user = $request->user();

        $cnabService = new CnabService($data['banco'],$data['dados'],$data['mes'],$data['observacao'],$data['data'],$user);
        $resposta = $cnabService->criar_cnab();
        $registro=RegistroCnab::find($resposta['cnab']);
        $name=explode('/',$registro->arquivo)[count(explode('/',$registro->arquivo))-1];
        $resposta['name']=$name;
        if($resposta['status']==true){
            return $resposta;
        }
        else{
            throw ValidationException::withMessages([
                'cnab' => ['Erro ao gerar o Cnab. Verifique se todos os dados estão corretos'],
            ]);
        }

    }

    public function downloadCnab($id){
        $registro = RegistroCnab::find($id);
        $file= public_path().'/'.$registro->arquivo;
        $name=explode('/',$registro->arquivo)[count(explode('/',$registro->arquivo))-1];
        Log::info($name);
        $headers = [
            'Content-type'        => 'text/txt',
        ];
        return response()->download($file,$name,$headers);

    }
}
