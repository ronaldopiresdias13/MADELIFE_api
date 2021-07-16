<?php

namespace App\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificacaoMedicamentoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $hour_now = Carbon::now()->addMinutes(30)->subMinute()->format('H:i');
        $hour_now1 = Carbon::now()->addMinutes(30)->format('H:i');

        $hour_ago = Carbon::now()->format('H:i');
        $hour_ago_minute = Carbon::now()->subMinute()->format('H:i');

        $date_now = Carbon::now()->format('Y-m-d');
        $date_ago = Carbon::now()->subDay()->format('Y-m-d');
        $tomorrow = Carbon::now()->addDay()->format('Y-m-d');

        $hour_ago_ago = Carbon::now()->subMinutes(30)->format('H:i');

        // $tomorrow = '2021-02-13';

        // $date_now = '2021-02-12';
        // $date_ago = '2021-02-11';

        // $hour_now1= '19:00';
        // $hour_now = '18:59';
        // $hour_ago = '18:30';
        $nao_marcados = DB::select(DB::raw("select hm.horario as hora, tp.id as transcricao_produto_id, tr.empresa_id from transcricao_produto as tp
    join transcricoes as tr on tr.id=tp.transcricao_id
    join horariomedicamentos as hm on hm.transcricao_produto_id=tp.id and hm.horario not in (select a.hora from acaomedicamentos as a where a.transcricao_produto_id=tp.id and a.`data`= :date_now)
    where  ((hm.horario> :now and hm.horario<= :ago) or (hm.horario=:now1)) and tp.ativo=1 and hm.ativo=1 order by transcricao_produto_id"), 
    array('date_now' => $date_now, 'now' => $hour_now, 'ago' => $hour_ago, 'now1' => $hour_now1));
        // dd($nao_marcados);

        foreach ($nao_marcados as $dado) {
            // dd(array('date_ago'=>$date_ago,'date_now'=>$date_now,'hour'=>$dado->hora,'id'=>$dado->transcricao_produto_id));
            $dados_prestadores = DB::select(
                DB::raw("select tp.id as transcricao_produto_id, es.id as escala_id, pe.id as pessoa_id, pe.nome, es.dataentrada, es.horaentrada, es.datasaida,es.horasaida from transcricao_produto as tp
        join transcricoes as t on tp.transcricao_id=t.id
        join escalas as es on es.ordemservico_id=t.ordemservico_id and es.ativo=1 and  ((es.dataentrada=:date_now and es.horaentrada<=:hour and es.horasaida>:hour_1) or (es.dataentrada=:date_ago_ and es.datasaida=:date_now_ and :hour_2<es.horasaida and es.horaentrada>es.horasaida) or (es.dataentrada=:date_now_2 and es.horaentrada<=:hour_3 and es.datasaida=:tomorrow))
        left join prestadores as pre on pre.id=es.prestador_id
        join prestador_formacao as pf on pf.prestador_id=pre.id
        join formacoes as fo on fo.id=pf.formacao_id and (fo.descricao='Auxiliar de Enfermagem' or fo.descricao='Técnico de Enfermagem' or fo.descricao='Enfermagem')
        join pessoas as pe on pe.id=pre.pessoa_id
        where tp.id=:id and t.empresa_id=:empresa_id"),
                array(
                    'empresa_id' => $dado->empresa_id,
                    'date_now' => $date_now,
                    'date_now_' => $date_now,
                    'date_now_2' => $date_now,
                    'tomorrow' => $tomorrow,

                    'hour' => $dado->hora,
                    'hour_1' => $dado->hora,
                    'hour_2' => $dado->hora,
                    'hour_3' => $dado->hora,


                    'date_ago_' => $date_ago,
                    'id' => $dado->transcricao_produto_id
                )
            );

            $app_id = 'eaf2c99b-f04b-4953-9ee8-fcfcd856c08c';
            $api_id = "Yjk2NjA1ODEtODJjNC00Njk0LTkzY2UtZDIzMGI3ODEyYzZi";
            foreach ($dados_prestadores as $prestador) {
                $client = new Client();
                $notification_response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                    'headers' => [
                        'Authorization' => 'Basic ' . $api_id,
                        'Content-Type' => 'application/json',

                    ],
                    'json' => [
                        "app_id" => $app_id,
                        "contents" => ["en" => 'Em breve um medicamento deve ser administrado'],
                        "headings" => ["en" => 'Lembrete'],
                        "include_external_user_ids" => [(string)$prestador->pessoa_id],
                        "icon" => "https://img.onesignal.com/t/73b9b966-f19e-4410-8b5d-51ebdef4652e.png",
                        "android_accent_color" => "00a99e",
                        "data" => ['foo' => 'bar']
                    ],
                    "http_errors" => false
                ]);

                $boleto = json_decode($notification_response->getBody());
                $notification_response->getBody()->close();
            }
        }









        $hour_now = Carbon::now()->subMinute()->format('H:i');
        $hour_ago = Carbon::now()->subMinutes(30)->format('H:i');
        $date_now = Carbon::now()->format('Y-m-d');
        $date_ago = Carbon::now()->subDay()->format('Y-m-d');
        $tomorrow = Carbon::now()->addDay()->format('Y-m-d');

        // $date_now = '2021-02-12';
        // $date_ago = '2021-02-11';

        // $hour_now = '00:14';
        // $hour_ago = '00:00';
        // DB::enableQueryLog();
        $nao_marcados = DB::select(DB::raw("select hm.horario as hora, tp.id as transcricao_produto_id, tr.empresa_id from transcricao_produto as tp
    join transcricoes as tr on tr.id=tp.transcricao_id
    join horariomedicamentos as hm on hm.transcricao_produto_id=tp.id and hm.horario not in (select a.hora from acaomedicamentos as a where a.transcricao_produto_id=tp.id and a.`data`= :date_now)
    where  ((hm.horario< :now and hm.horario>= :ago)) and tp.ativo=1 and hm.ativo=1 order by transcricao_produto_id"), array('date_now' => $date_now, 'now' => $hour_now, 'ago' => $hour_ago));

        foreach ($nao_marcados as $dado) {
            // dd(array('date_ago'=>$date_ago,'date_now'=>$date_now,'hour'=>$dado->hora,'id'=>$dado->transcricao_produto_id));
            $dados_prestadores = DB::select(
                DB::raw("select tp.id as transcricao_produto_id, es.id as escala_id, pe.id as pessoa_id, pe.nome, es.dataentrada, es.horaentrada, es.datasaida,es.horasaida from transcricao_produto as tp
        join transcricoes as t on tp.transcricao_id=t.id
        join escalas as es on es.ordemservico_id=t.ordemservico_id and es.ativo=1 and  ((es.dataentrada=:date_now and es.horaentrada<=:hour and es.horasaida>:hour_1) or (es.dataentrada=:date_ago_ and es.datasaida=:date_now_ and :hour_2<es.horasaida and es.horaentrada>es.horasaida) or (es.dataentrada=:date_now_2 and es.horaentrada<=:hour_3 and es.datasaida=:tomorrow))
        left join prestadores as pre on pre.id=es.prestador_id
        join prestador_formacao as pf on pf.prestador_id=pre.id
        join formacoes as fo on fo.id=pf.formacao_id and (fo.descricao='Auxiliar de Enfermagem' or fo.descricao='Técnico de Enfermagem' or fo.descricao='Enfermagem')
        join pessoas as pe on pe.id=pre.pessoa_id
        where tp.id=:id and t.empresa_id=:empresa_id"),
                array(
                    'empresa_id' => $dado->empresa_id,
                    'date_now' => $date_now,
                    'date_now_' => $date_now,
                    'date_now_2' => $date_now,
                    'tomorrow' => $tomorrow,

                    'hour' => $dado->hora,
                    'hour_1' => $dado->hora,
                    'hour_2' => $dado->hora,
                    'hour_3' => $dado->hora,

                    'date_ago_' => $date_ago,
                    'id' => $dado->transcricao_produto_id
                )
            );

            $app_id = 'eaf2c99b-f04b-4953-9ee8-fcfcd856c08c';
            $api_id = "Yjk2NjA1ODEtODJjNC00Njk0LTkzY2UtZDIzMGI3ODEyYzZi";
            foreach ($dados_prestadores as $prestador) {
                $client = new Client();
                $notification_response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
                    'headers' => [
                        'Authorization' => 'Basic ' . $api_id,
                        'Content-Type' => 'application/json',

                    ],
                    'json' => [
                        "app_id" => $app_id,
                        "contents" => ["en" => 'Existe um medicamento em atraso ou com confirmação não registrada'],
                        "headings" => ["en" => 'Lembrete'],
                        "include_external_user_ids" => [(string)$prestador->pessoa_id],
                        "icon" => "https://img.onesignal.com/t/73b9b966-f19e-4410-8b5d-51ebdef4652e.png",
                        "android_accent_color" => "00a99e",
                        "data" => ['foo' => 'bar']
                    ],
                    "http_errors" => false
                ]);

                $boleto = json_decode($notification_response->getBody());
                $notification_response->getBody()->close();

            }
        }
    }
}
