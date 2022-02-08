<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrescricaoARequest;
use App\Http\Resources\PrescricaoAResource;
use App\Models\PrescricaoA;
use Illuminate\Http\Request;

class PrescricaoAController extends Controller
{
    public function listPrescricoesA(Request $request)
    {
        $user = $request->user();
        if($request->search==null){
            $request->search='';
        }
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $prescricoes = PrescricaoA::where(function($q)use($request) {
            $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->search.'%']);
        })->where('empresa_id','=',$empresa_id)->orderBy('created_at', 'desc')->paginate(15);
        $current_page_prescricao = $prescricoes->currentPage();
        $last_page_prescricao = $prescricoes->lastPage();
        $total_prescricao = $prescricoes->total();
        $per_page_prescricao = $prescricoes->perPage();
        return response()->json([
            'prescricoes' => PrescricaoAResource::collection($prescricoes),
            'current_page_prescricao' => $current_page_prescricao,
            'last_page_prescricao' => $last_page_prescricao,
            'total_prescricao' => $total_prescricao,
            'per_page_prescricao' => $per_page_prescricao,
        ]);
    }


    public function store_prescricao_a(PrescricaoARequest $request){
        $data=$request->validated();
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;
        $data['empresa_id']=$empresa_id;
        $prescricao = new PrescricaoA();
        $prescricao->fill($data)->save();


        return response()->json(['status'=>true,'prescricao'=>$prescricao]);
    }

    public function update_prescricao_a(PrescricaoARequest $request){
        $data=$request->validated();
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;

        $prescricao = PrescricaoA::where('id','=',$data['prescricao_id'])->where('empresa_id','=',$empresa_id)->first();
        $prescricao->fill($data)->save();

        return response()->json(['status'=>true]);
    }
    
    public function getPrescricaoA($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $prescricao = PrescricaoA::where('id','=',$id)->where('empresa_id','=',$empresa_id)->first();
       
        return response()->json([
            'prescricao' => PrescricaoAResource::make($prescricao),
        ]);
    }


    public function delete_prescricao_a($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = PrescricaoA::where('empresa_id','=',$empresa_id)->where('id','=',$id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
