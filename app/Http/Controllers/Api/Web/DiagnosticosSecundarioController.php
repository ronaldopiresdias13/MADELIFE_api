<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiagnosticoRequest;
use App\Http\Resources\DiagnosticoResource;
use App\Models\DiagnosticoPil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiagnosticosSecundarioController extends Controller
{
    public function listDiagnosticos(Request $request)
    {
        $user = $request->user();
        if($request->search==null){
            $request->search='';
        }
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diagnosticos = DiagnosticoPil::where('flag','=','Secundário')->where(function($q)use($request) {
            $q->whereRaw('lower(codigo) LIKE lower(?)',['%'.$request->search.'%'])
            ->orWhereRaw('lower(nome) LIKE lower(?)',['%'.$request->search.'%']);
        })->orderBy('created_at', 'desc')->paginate(15, ['*'], 'diagnosticos');
        $current_page_diagnostico = $diagnosticos->currentPage();
        $last_page_diagnostico = $diagnosticos->lastPage();
        $total_diagnostico = $diagnosticos->total();
        $per_page_diagnostico = $diagnosticos->perPage();
        return response()->json([
            'diagnosticos' => DiagnosticoResource::collection($diagnosticos),
            'current_page_diagnostico' => $current_page_diagnostico,
            'last_page_diagnostico' => $last_page_diagnostico,
            'total_diagnostico' => $total_diagnostico,
            'per_page_diagnostico' => $per_page_diagnostico,
        ]);
    }


    public function store_diagnostico(DiagnosticoRequest $request){
        $data=$request->validated();

        $diagnostico = new DiagnosticoPil();
        $diagnostico->fill($data);
        $diagnostico->fill(['flag'=>'Secundário'])->save();


        return response()->json(['status'=>true,'diagnostico'=>$diagnostico]);
    }

    public function update_diagnostico(DiagnosticoRequest $request){
        $data=$request->validated();

        $diagnostico = DiagnosticoPil::where('flag','=','Secundário')->where('id','=',$data['diagnostico_id'])->first();
        $diagnostico->fill($data)->save();

        return response()->json(['status'=>true]);
    }
    
    public function getDiagnostico($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diagnostico = DiagnosticoPil::where('flag','=','Secundário')->where('id','=',$id)->first();
       
        return response()->json([
            'diagnostico' => DiagnosticoResource::make($diagnostico),
        ]);
    }


    public function delete_diagnostico($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = DiagnosticoPil::where('flag','=','Secundário')->where('id','=',$id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
