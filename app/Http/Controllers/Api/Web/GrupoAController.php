<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\GrupoPrescricaoARequest;
use App\Http\Resources\GrupoAResource;
use App\Http\Resources\PrescricaoAResource;
use App\Models\GrupoPrescricaoA;
use App\Models\PrescricaoA;
use Illuminate\Http\Request;

class GrupoAController extends Controller
{
    public function listGruposA(Request $request)
    {
        $user = $request->user();
        if($request->search==null){
            $request->search='';
        }
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $prescricoes = GrupoPrescricaoA::where(function($q)use($request) {
            $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->search.'%']);
        })->where('empresa_id','=',$empresa_id)->orderBy('created_at', 'desc')->paginate(15);
        $current_page_grupo = $prescricoes->currentPage();
        $last_page_grupo = $prescricoes->lastPage();
        $total_grupo = $prescricoes->total();
        $per_page_grupo = $prescricoes->perPage();
        return response()->json([
            'grupos' => GrupoAResource::collection($prescricoes),
            'current_page_grupo' => $current_page_grupo,
            'last_page_grupo' => $last_page_grupo,
            'total_grupo' => $total_grupo,
            'per_page_grupo' => $per_page_grupo,
        ]);
    }

    public function getDadosGrupoA()
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $prescricoes = PrescricaoA::where('empresa_id','=',$empresa_id)->orderBy('nome','asc')->get();
       
        return response()->json([
            'prescricoes' => PrescricaoAResource::collection($prescricoes),
        ]);
    }


    public function store_grupo_a(GrupoPrescricaoARequest $request){
        $data=$request->validated();
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;
        $data['empresa_id']=$empresa_id;
        $prescricao = new GrupoPrescricaoA();
        $prescricao->fill($data)->save();


        return response()->json(['status'=>true,'grupo'=>$prescricao]);
    }

    public function update_grupo_a(GrupoPrescricaoARequest $request){
        $data=$request->validated();
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;

        $prescricao = GrupoPrescricaoA::where('id','=',$data['grupo_id'])->where('empresa_id','=',$empresa_id)->first();
        $prescricao->fill($data)->save();

        return response()->json(['status'=>true]);
    }
    
    public function getGrupoA($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $prescricao = GrupoPrescricaoA::where('id','=',$id)->where('empresa_id','=',$empresa_id)->first();
       
        return response()->json([
            'grupo' => GrupoAResource::make($prescricao),
        ]);
    }


    public function delete_grupo_a($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = GrupoPrescricaoA::where('empresa_id','=',$empresa_id)->where('id','=',$id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
