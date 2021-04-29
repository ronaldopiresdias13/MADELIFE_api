<?php

use App\Models\Ocorrencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {

    // return view('welcome');
    $hour_now = Carbon::now()->subMinute()->format('H:i');
    $hour_ago = Carbon::now()->subMinutes(15)->format('H:i');
    $date_now = Carbon::now()->format('Y-m-d');
    $date_ago = Carbon::now()->format('Y-m-d');

    $date_now='2021-02-12';
    $date_ago='2021-02-11';

    $hour_now='00:14';
    $hour_ago='00:00';
    DB::enableQueryLog();
    $nao_marcados = DB::select(DB::raw("select hm.horario as hora, tp.id as transcricao_produto_id, tr.empresa_id from transcricao_produto as tp
    join transcricoes as tr on tr.id=tp.transcricao_id
    join horariomedicamentos as hm on hm.transcricao_produto_id=tp.id and hm.horario not in (select a.hora from acaomedicamentos as a where a.transcricao_produto_id=tp.id and a.`data`= :date_now)
    where tr.empresa_id=:empresa_id and ((hm.horario< :now and hm.horario>= :ago)) and tp.ativo=1 and hm.ativo=1 order by transcricao_produto_id"),array('empresa_id'=>1, 'date_now'=>$date_now,'now'=>$hour_now,'ago'=>$hour_ago));
    
    foreach($nao_marcados as $dado){
        // dd(array('date_ago'=>$date_ago,'date_now'=>$date_now,'hour'=>$dado->hora,'id'=>$dado->transcricao_produto_id));
        $dados_prestadores = DB::select(DB::raw("select tp.id as transcricao_produto_id, es.id as escala_id, pe.id as pessoa_id, pe.nome, es.dataentrada, es.horaentrada, es.datasaida,es.horasaida from transcricao_produto as tp
        join transcricoes as t on tp.transcricao_id=t.id
        join escalas as es on es.ordemservico_id=t.ordemservico_id and es.ativo=1 and  ((es.dataentrada=:date_now and es.horaentrada<:hour and es.horasaida>:hour_1) or (es.dataentrada=:date_ago_ and es.datasaida=:date_now_ and :hour_2<es.horasaida and es.horaentrada>es.horasaida))
        left join prestadores as pre on pre.id=es.prestador_id
        join pessoas as pe on pe.id=pre.pessoa_id
        where tp.id=:id and t.empresa_id=:empresa_id"),
        array(
            'empresa_id'=>$dado->empresa_id,
            'date_now'=>$date_now,
            'date_now_'=>$date_now,

            'hour'=>$dado->hora,
            'hour_1'=>$dado->hora,
            'hour_2'=>$dado->hora,

            'date_ago_'=>$date_ago,
            'id'=>$dado->transcricao_produto_id
        ));


        $paciente = DB::select(DB::raw("select p.id as paciente_id, p.nome as paciente_nome, ps.id as responsavel_id, ps.nome as responsavel_nome, tp.id as transcricao_produto_id from transcricao_produto as tp
        join transcricoes as t on tp.transcricao_id=t.id
        join ordemservicos as os on os.id=t.ordemservico_id
        join orcamentos as o on o.id=os.orcamento_id
        join homecares as hc on hc.orcamento_id=o.id
        join pacientes as pac on pac.id=hc.paciente_id
        join pessoas as p on pac.pessoa_id=p.id
        left join responsaveis as r on r.id=pac.responsavel_id
        left join pessoas as ps on ps.id=r.pessoa_id
        where tp.id=:id and t.empresa_id=:empresa_id"),
        array(
            'empresa_id'=>$dado->empresa_id,
            'id'=>$dado->transcricao_produto_id
        ));

        
        $pessoas=[];
        foreach($dados_prestadores as $dado_prestador){
            if($dado_prestador->pessoa_id!=null){
                array_push($pessoas,$dado_prestador->pessoa_id);
            }
        }
        //chacar se tem alguma escala essa hora?
        // if (!property_exists($resp, 'responsavel_id')) {

        // }
        // dd([$dados_prestadores,$dado]);

        $ocorrencia=new Ocorrencia();
        $ocorrencia->fill([
            'tipo'=>'Medicamento Atrasado',
            'transcricao_produto_id'=>$dado->transcricao_produto_id,
            'empresa_id'=>$dado->empresa_id,
            'paciente_id'=>$paciente[0]->paciente_id,
            'responsavel_id'=>$paciente[0]->responsavel_id,

            'horario'=>$date_now.' '.$dado->hora,
            'situacao'=>'Pendente'
        ])->save();
        $ocorrencia->pessoas()->Sync($pessoas);
        
    }

    // dd($nao_marcados);
    // dd(DB::getQueryLog());
    return $nao_marcados;
});