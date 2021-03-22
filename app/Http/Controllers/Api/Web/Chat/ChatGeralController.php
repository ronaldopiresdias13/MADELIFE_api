<?php

namespace App\Http\Controllers\Api\Web\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatGeralRequest;
use App\Http\Resources\ConversaResource;
use App\Models\Conversa;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatGeralController extends Controller
{
    public function get_conversas(){
        $user = request()->user();
        $pessoa=$user->pessoa()->first();
        $conversas = Conversa::select('conversas.*')->where('conversas.sender_id',$pessoa->id)->orWhere('conversas.receive_id',$pessoa->id)->join('conversas_mensagens','conversas_mensagens.conversa_id','=','conversas.id')->orderBy('conversas_mensagens.created_at', 'asc')->get()->unique('id');
        // $conversas = DB::select(DB::raw('select distinct c.id,c.* from conversas as c join conversas_mensagens as cm on cm.conversa_id=c.id order by cm.created_at asc'));

        return response()->json([
            'conversas'=>ConversaResource::collection($conversas)
        ]);
    }

    public function get_pessoas(Request $request){
        $user = $request->user();
        $pessoa=$user->pessoa()->first();
        $pessoas = Pessoa::has('profissional')->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->search.'%'])->with('profissional')->orderBy('nome', 'asc')->get();
        // where('id','<>',$pessoa->id)->
        return response()->json([
            'pessoas'=>$pessoas
        ]);
    }

    public function enviarArquivos(ChatGeralRequest $request){
        $data=$request->validated();
        $files_path=[];
        if ($arquivos = $request->file('arquivos')) {
            foreach($arquivos as $arquivo){
                $name = uniqid('arquivo') . '.' . $arquivo->extension();
                $filename = $arquivo->storeAs('arquivos_chat_geral', $name, ['disk' => 'public']);
                array_push($files_path,$filename);
            }
        }

        return response()->json([
            'arquivos'=>$files_path
        ]);

    }
}
