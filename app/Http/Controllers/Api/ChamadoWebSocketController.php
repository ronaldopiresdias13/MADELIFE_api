<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChamadoAtendenteResource;
use App\Http\Resources\ChamadoResource;
use App\Jobs\NotificacaoAppJob;
use App\Models\Chamado;
use App\Models\Conversa;
use App\Models\ConversaMensagem;
use App\Models\MensagemChamado;
use App\Models\Profissional;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use SplObjectStorage;

class ChamadoWebSocketController extends Controller implements MessageComponentInterface
{
    private $clients;
    private $clientes_ids = [];
    private $resouce_pessoa = [];
    private $enfermagem = [];
    private $ti = [];

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $message = json_decode($msg);
        if ($message->type == 'token') {
            try {
                // Log::info('token '.$message->token);
                // Log::info(json_encode(auth('api')->user()));

                // $http = new Client();
                // $response = $http->get(url('/api/auth/user'), [
                //     'headers' => [
                //         'Accept' => 'application/json',
                //         'Authorization' => $message->token_type . ' ' . $message->token,
                //     ],
                //     "http_errors" => false
                // ]);
                // Log::info($message->token);
                $token = $message->token;
                // break up the token into its three parts
                $token_parts = explode('.', $token);
                Log::info($token_parts);
                $token_header = $token_parts[1];

                // base64 decode to get a json string
                $token_header_json = base64_decode($token_header);
                Log::info($token_header_json);

                $token_header_array = json_decode($token_header_json, true);
                Log::info($token_header_array);
                $user_token = $token_header_array['jti'];

                
                $user_id = DB::table('oauth_access_tokens')->where('id',$user_token )->value('user_id');
                Log::info($user_id);
                $resp = User::where('id', '=', $user_id)->with('pessoa')->first();
                if ($resp == null) {
                    $from->send(json_encode(['type' => 'disconnect', 'mensagem' => 'Usuário inválido ou desconectado']));
                    return;
                } else {
                    $from->send(json_encode(['type' => 'connected']));
                }
                // Log::info($message->token_type . ' ' . $message->token);
                // Log::info($response->getBody());
                // $resp = json_decode($response->getBody());
                // $response->getBody()->close();

                // if (property_exists($resp, 'message')) {
                //     $from->send(json_encode(['type' => 'disconnect', 'mensagem' => 'Usuário inválido ou desconectado']));
                //     return;
                // } else {
                //     $from->send(json_encode(['type' => 'connected']));
                // }
                $this->resouce_pessoa[$from->resourceId] = $resp;

                if ($message->area == 'Enfermagem') {
                    $profissional = Profissional::where('pessoa_id', '=', $resp->pessoa->id)->first();
                    if ($profissional == null) {
                        $from->send(json_encode(['type' => 'disconnect', 'mensagem' => 'Usuário inválido ou desconectado']));
                        return;
                    }
                    if (isset($this->clientes_ids[$resp->pessoa->id . $message->area . $profissional->empresa_id])) {
                        array_push($this->clientes_ids[$resp->pessoa->id . $message->area . $profissional->empresa_id], $from);
                    } else {
                        $this->clientes_ids[$resp->pessoa->id . $message->area . $profissional->empresa_id] = [];
                        array_push($this->clientes_ids[$resp->pessoa->id . $message->area . $profissional->empresa_id], $from);
                    }

                    array_push($this->enfermagem, $from->resourceId);
                } else if ($message->area == 'T.I.') {
                    if (isset($this->clientes_ids[$resp->pessoa->id . $message->area])) {
                        array_push($this->clientes_ids[$resp->pessoa->id . $message->area], $from);
                    } else {
                        $this->clientes_ids[$resp->pessoa->id . $message->area] = [];
                        array_push($this->clientes_ids[$resp->pessoa->id . $message->area], $from);
                    }


                    array_push($this->ti, $from->resourceId);
                } else {
                    // $this->clientes_ids[$resp->pessoa->id] = $from;
                    if (isset($this->clientes_ids[$resp->pessoa->id])) {
                        array_push($this->clientes_ids[$resp->pessoa->id], $from);
                    } else {
                        $this->clientes_ids[$resp->pessoa->id] = [];
                        array_push($this->clientes_ids[$resp->pessoa->id], $from);
                    }
                }
                Log::info($from->resourceId);

                Log::info('connect');

                // Log::info($resp->id);
            } catch (Exception $e) {
                Log::error($e);
                $from->send(json_encode(['type' => 'disconnect', 'mensagem' => 'Usuário inválido ou desconectado']));
                return;
            }
        } else if ($message->type == 'finalizar_chamado') {
            Log::info($from->resourceId);

            Log::info($msg);
            $chamado = Chamado::where('id', '=', $message->chamado_id)->first();
            if (isset($this->clientes_ids[$chamado->prestador_id])) {
                foreach ($this->clientes_ids[$chamado->prestador_id] as $socket) {
                    $socket->send(json_encode([
                        'atendente' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                        'prestador' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                        'chamado' => ChamadoResource::make($chamado),

                        'type' => 'finalizar_chamado'
                    ]));
                }
            }
        } else if ($message->type == 'msg_receive_atendente') {
            Log::info($from->resourceId);

            Log::info($msg);
            $chamado = Chamado::where('id', '=', $message->chamado_id)->with('prestador')->first();

            Log::info('reeive1');
            if ($chamado != null) {

                $mensagem = $chamado->mensagens()->where('mensagens_chamado.atendente_id', null)->where('uuid', $message->uuid)->first();
                Log::info('reeive2');

                if ($mensagem != null) {
                    $chamado->mensagens()->where('mensagens_chamado.id', '<=', $mensagem->id)->where('mensagens_chamado.atendente_id', null)->update(['visto' => true]);
                    Log::info('reeive3');

                    if (isset($this->clientes_ids[$chamado->prestador_id])) {
                        foreach ($this->clientes_ids[$chamado->prestador_id] as $socket) {

                            $socket->send(json_encode([
                                'atendente' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                'prestador' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                                'chamado' => ChamadoResource::make($chamado),
                                'uuid' => $message->uuid,
                                'type' => 'msg_receive'
                            ]));
                        }
                    }
                }
            }
        } else if ($message->type == 'msg_receive') {
            Log::info($from->resourceId);

            Log::info($msg);
            $chamado = Chamado::where('id', '=', $message->chamado_id)->with('prestador')->first();

            Log::info('reeive1');
            if ($chamado != null) {
                if ($message->atendente != null) {
                    $mensagem = $chamado->mensagens()->where('mensagens_chamado.atendente_id', '=', $message->atendente->id)->where('uuid', $message->uuid)->first();
                    Log::info('reeive2');

                    if ($mensagem != null) {
                        $chamado->mensagens()->where('mensagens_chamado.id', '<=', $mensagem->id)->where('mensagens_chamado.atendente_id', '=', $message->atendente->id)->update(['visto' => true]);
                        Log::info('reeive3');
                        $key = $message->atendente->id . $chamado->tipo . ($chamado->empresa_id == null ? '' : $chamado->empresa_id);

                        if (isset($this->clientes_ids[$key])) {
                            foreach ($this->clientes_ids[$key] as $socket) {

                                $socket->send(json_encode([
                                    'prestador' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                    'atendente' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                                    'chamado' => ChamadoAtendenteResource::make($chamado),
                                    'uuid' => $message->uuid,
                                    'type' => 'msg_receive'
                                ]));
                            }
                        }
                    }
                } else {
                    $mensagem = $chamado->mensagens()->where('mensagens_chamado.atendente_id', '<>', null)->where('uuid', $message->uuid)->first();
                    Log::info('reeive2');

                    if ($mensagem != null) {
                        $chamado->mensagens()->where('mensagens_chamado.id', '<=', $mensagem->id)->where('mensagens_chamado.atendente_id', '<>', null)->update(['visto' => true]);
                        Log::info('reeive3');

                        if ($chamado->tipo == 'Enfermagem') {
                            foreach ($this->enfermagem as $atendente_id_resource) {
                                Log::info('atendnete: ' . $atendente_id_resource);
                                if (isset($this->resouce_pessoa[$atendente_id_resource])) {
                                    $pessoa = $this->resouce_pessoa[$atendente_id_resource]->pessoa->id;
                                    $key = $pessoa . $chamado->tipo . ($chamado->empresa_id == null ? '' : $chamado->empresa_id);

                                    if (isset($this->clientes_ids[$key])) {
                                        foreach ($this->clientes_ids[$key] as $socket) {
                                            if ($socket->resourceId == $atendente_id_resource) {

                                                $socket->send(json_encode([
                                                    'prestador' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                                    'chamado' => ChamadoAtendenteResource::make($chamado),

                                                    'uuid' => $message->uuid,
                                                    'type' => 'msg_receive'
                                                ]));
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($this->ti as $atendente_id_resource) {
                                Log::info('atendnete: ' . $atendente_id_resource);
                                if (isset($this->resouce_pessoa[$atendente_id_resource])) {
                                    $pessoa = $this->resouce_pessoa[$atendente_id_resource]->pessoa->id;
                                    if (isset($this->clientes_ids[$pessoa . $chamado->tipo])) {
                                        foreach ($this->clientes_ids[$pessoa . $chamado->tipo] as $socket) {
                                            if ($socket->resourceId == $atendente_id_resource) {

                                                $socket->send(json_encode([
                                                    'prestador' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                                    'chamado' => ChamadoAtendenteResource::make($chamado),

                                                    'uuid' => $message->uuid,
                                                    'type' => 'msg_receive'
                                                ]));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else if ($message->type == 'message') {
            Log::info($from->resourceId);

            Log::info($msg);
            $chamado = Chamado::where('id', '=', $message->chamado_id)->first();
            if ($chamado == null || $chamado->finalizado == true) {
                return;
            }
            $mensagem = new MensagemChamado();
            try {
                Log::info('sender_id: ' . $this->resouce_pessoa[$from->resourceId]->pessoa->id);
                Log::info('message: ' . $message->mensagem);


                $mensagem->fill([
                    'atendente_id' => null,
                    'prestador_id' => $this->resouce_pessoa[$from->resourceId]->pessoa->id,
                    'message' => $message->mensagem,
                    'uuid' => $message->uuid,
                    'visto' => false,
                    'chamado_id' => $chamado->id
                ])->save();
            } catch (Exception $e) {
                Log::info('top');
                Log::info($e);

                return;
            }
            Log::info('mensagem2.1');
            if ($chamado->tipo == 'Enfermagem') {

                foreach ($this->enfermagem as $atendente_id_resource) {
                    Log::info('atendnete: ' . $atendente_id_resource);
                    if (isset($this->resouce_pessoa[$atendente_id_resource])) {
                        $pessoa = $this->resouce_pessoa[$atendente_id_resource]->pessoa->id;
                        $key = $pessoa . $chamado->tipo . ($chamado->empresa_id == null ? '' : $chamado->empresa_id);

                        if (isset($this->clientes_ids[$key])) {
                            foreach ($this->clientes_ids[$key] as $socket) {
                                if ($socket->resourceId == $atendente_id_resource) {
                                    Log::info($socket->resourceId);
                                    if ($socket->resourceId == $atendente_id_resource) {

                                        $socket->send(json_encode([
                                            'prestador' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                            'chamado' => ChamadoAtendenteResource::make($chamado),
                                            'mensagem' => $message->mensagem,
                                            'uuid' => $mensagem->uuid,
                                            'type' => 'message',
                                            'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

                                        ]));
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                foreach ($this->ti as $atendente_id_resource) {
                    Log::info('atendnete: ' . $atendente_id_resource);
                    if (isset($this->resouce_pessoa[$atendente_id_resource])) {
                        $pessoa = $this->resouce_pessoa[$atendente_id_resource]->pessoa->id;
                        if (isset($this->clientes_ids[$pessoa . $chamado->tipo])) {
                            foreach ($this->clientes_ids[$pessoa . $chamado->tipo] as $socket) {
                                if ($socket->resourceId == $atendente_id_resource) {

                                    $socket->send(json_encode([
                                        'prestador' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                        'chamado' => ChamadoAtendenteResource::make($chamado),
                                        'mensagem' => $message->mensagem,
                                        'uuid' => $mensagem->uuid,
                                        'type' => 'message',
                                        'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

                                    ]));
                                }
                            }
                        }
                    }
                }
            }
            // if (isset($this->clientes_ids[$message->receive])) {

            // }
            $from->send(json_encode([
                'chamado_id' => $chamado->id,
                'mensagem' => $message->mensagem,
                'uuid' => $mensagem->uuid,
                'type' => 'msg_save',
                'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

            ]));

            Log::info('mensagem3');

            // foreach ($this->clients as $client) {
            //     if ($client->resourceId != $from->resourceId) {
            //         $client->send($msg);
            //     }
            // }
        } else if ($message->type == 'message_atendente_to_user') {
            Log::info($from->resourceId);

            Log::info($msg);
            $chamado = Chamado::where('id', '=', $message->chamado_id)->first();
            if ($chamado == null || $chamado->finalizado == true) {
                return;
            }
            $mensagem = new MensagemChamado();
            try {
                Log::info('sender_id: ' . $this->resouce_pessoa[$from->resourceId]->pessoa->id);
                Log::info('message: ' . $message->mensagem);


                $mensagem->fill([
                    'atendente_id' => $this->resouce_pessoa[$from->resourceId]->pessoa->id,
                    'prestador_id' => $chamado->prestador_id,
                    'message' => $message->mensagem,
                    'uuid' => $message->uuid,
                    'visto' => false,
                    'chamado_id' => $chamado->id
                ])->save();
            } catch (Exception $e) {
                Log::info('top');
                return;
            }
            Log::info('mensagem2.1');
            Log::info('chamado');
            Log::info($chamado);
            $prestador = $chamado->prestador()->first();
            NotificacaoAppJob::dispatch($prestador, $chamado->tipo . ' - ' . $chamado->assunto, $message->mensagem);
            if (isset($this->clientes_ids[$chamado->prestador_id])) {
                foreach ($this->clientes_ids[$chamado->prestador_id] as $socket) {

                    $socket->send(json_encode([
                        'atendente' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                        'prestador' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                        'chamado' => ChamadoResource::make($chamado),

                        'mensagem' => $message->mensagem,
                        'uuid' => $mensagem->uuid,
                        'type' => 'message',
                        'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')
                    ]));
                }
            }
            $from->send(json_encode([
                'chamado_id' => $chamado->id,
                'mensagem' => $message->mensagem,
                'uuid' => $mensagem->uuid,
                'type' => 'msg_save',
                'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

            ]));

            Log::info('mensagem3');

            // foreach ($this->clients as $client) {
            //     if ($client->resourceId != $from->resourceId) {
            //         $client->send($msg);
            //     }
            // }
        } else if ($message->type == 'image_user' || $message->type == 'video_user') {
            Log::info($from->resourceId);

            Log::info($msg);
            $chamado = Chamado::where('id', '=', $message->chamado_id)->first();
            if ($chamado == null || $chamado->finalizado == true) {
                return;
            }
            $mensagem = new MensagemChamado();
            try {
                Log::info('sender_id: ' . $this->resouce_pessoa[$from->resourceId]->pessoa->id);


                $mensagem->fill([
                    'atendente_id' => $this->resouce_pessoa[$from->resourceId]->pessoa->id,
                    'prestador_id' => $chamado->prestador_id,
                    'arquivo' => $message->arquivo,
                    'type' => Str::replaceArray('_user', [''], $message->type),
                    'uuid' => $message->uuid,
                    'visto' => false,
                    'chamado_id' => $chamado->id
                ])->save();
            } catch (Exception $e) {
                Log::info('top');
                return;
            }
            Log::info('file2.1');

            if (isset($this->clientes_ids[$chamado->prestador_id])) {
                foreach ($this->clientes_ids[$chamado->prestador_id] as $socket) {

                    $socket->send(json_encode([
                        'atendente' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                        'prestador' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                        'chamado' => ChamadoResource::make($chamado),

                        'arquivo' =>  $mensagem->arquivo,
                        'uuid' => $mensagem->uuid,
                        'type' => $mensagem->type,
                        'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')
                    ]));
                }
            }

            $from->send(json_encode([
                'chamado_id' => $chamado->id,
                'uuid' => $mensagem->uuid,
                'type' => 'msg_save',
                'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

            ]));

            Log::info('file3');

            // foreach ($this->clients as $client) {
            //     if ($client->resourceId != $from->resourceId) {
            //         $client->send($msg);
            //     }
            // }
        } else if ($message->type == 'image_atendente' || $message->type == 'video_atendente') {
            Log::info($from->resourceId);

            Log::info($msg);
            $chamado = Chamado::where('id', '=', $message->chamado_id)->first();
            if ($chamado == null || $chamado->finalizado == true) {
                return;
            }
            $mensagem = new MensagemChamado();
            try {
                Log::info('sender_id: ' . $this->resouce_pessoa[$from->resourceId]->pessoa->id);


                $mensagem->fill([
                    'atendente_id' => null,
                    'prestador_id' => $chamado->prestador_id,
                    'arquivo' => $message->arquivo,
                    'type' => Str::replaceArray('_atendente', [''], $message->type),
                    'uuid' => $message->uuid,
                    'visto' => false,
                    'chamado_id' => $chamado->id
                ])->save();
            } catch (Exception $e) {
                Log::info('top');
                return;
            }
            Log::info('file2.1');
            if ($chamado->tipo == 'Enfermagem') {

                foreach ($this->enfermagem as $atendente_id_resource) {
                    Log::info('atendnete: ' . $atendente_id_resource);
                    if (isset($this->resouce_pessoa[$atendente_id_resource])) {
                        $pessoa = $this->resouce_pessoa[$atendente_id_resource]->pessoa->id;
                        $key = $pessoa . $chamado->tipo . ($chamado->empresa_id == null ? '' : $chamado->empresa_id);

                        if (isset($this->clientes_ids[$key])) {
                            foreach ($this->clientes_ids[$key] as $socket) {
                                if ($socket->resourceId == $atendente_id_resource) {

                                    $socket->send(json_encode([
                                        'atendente' => null,
                                        'prestador' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                        'chamado' => ChamadoAtendenteResource::make($chamado),

                                        'arquivo' =>  $mensagem->arquivo,
                                        'uuid' => $mensagem->uuid,
                                        'type' => $mensagem->type,
                                        'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

                                    ]));
                                }
                            }
                        }
                    }
                }
            } else {
                foreach ($this->ti as $atendente_id_resource) {
                    Log::info('atendnete: ' . $atendente_id_resource);
                    if (isset($this->resouce_pessoa[$atendente_id_resource])) {
                        $pessoa = $this->resouce_pessoa[$atendente_id_resource]->pessoa->id;
                        if (isset($this->clientes_ids[$pessoa . $chamado->tipo])) {
                            foreach ($this->clientes_ids[$pessoa . $chamado->tipo] as $socket) {
                                if ($socket->resourceId == $atendente_id_resource) {

                                    $socket->send(json_encode([
                                        'atendente' => null,
                                        'prestador' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                        'chamado' => ChamadoAtendenteResource::make($chamado),

                                        'arquivo' =>  $mensagem->arquivo,
                                        'uuid' => $mensagem->uuid,
                                        'type' => $mensagem->type,
                                        'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

                                    ]));
                                }
                            }
                        }
                    }
                }
            }

            $from->send(json_encode([
                'chamado_id' => $chamado->id,
                'uuid' => $mensagem->uuid,
                'type' => 'msg_save',
                'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

            ]));

            Log::info('file3');

            // foreach ($this->clients as $client) {
            //     if ($client->resourceId != $from->resourceId) {
            //         $client->send($msg);
            //     }
            // }
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $conn->close();
        $this->clients->detach($conn);

        Log::info("desconectou " . $conn->resourceId);
        if (isset($this->resouce_pessoa[$conn->resourceId])) {
            Log::info("desconectou2 " . $conn->resourceId);

            $pessoa = $this->resouce_pessoa[$conn->resourceId]->pessoa;
            if (($key = array_search($conn->resourceId, $this->enfermagem)) !== false) {
                unset($this->enfermagem[$key]);
                // unset($this->clientes_ids[$pessoa->id . 'Enfermagem']);
                $profissional = Profissional::where('pessoa_id', '=', $pessoa->id)->first();

                for ($i = 0; $i < count($this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id]); $i++) {
                    if ($this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id][$i]->resourceId == $conn->resourceId) {
                        $this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id][$i]->close();
                        unset($this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id][$i]);
                        unset($this->resouce_pessoa[$conn->resourceId]);
                        break;
                    }
                }
            } else if (($key = array_search($conn->resourceId, $this->ti)) !== false) {
                unset($this->ti[$key]);
                // unset($this->clientes_ids[$pessoa->id . 'T.I.']);
                for ($i = 0; $i < count($this->clientes_ids[$pessoa->id . 'T.I.']); $i++) {
                    if ($this->clientes_ids[$pessoa->id . 'T.I.'][$i]->resourceId == $conn->resourceId) {
                        $this->clientes_ids[$pessoa->id . 'T.I.'][$i]->close();
                        unset($this->clientes_ids[$pessoa->id . 'T.I.'][$i]);
                        unset($this->resouce_pessoa[$conn->resourceId]);
                        break;
                    }
                }
            } else {
                // unset($this->clientes_ids[$pessoa->id]);
                for ($i = 0; $i < count($this->clientes_ids[$pessoa->id]); $i++) {
                    if ($this->clientes_ids[$pessoa->id][$i]->resourceId == $conn->resourceId) {
                        $this->clientes_ids[$pessoa->id][$i]->close();
                        unset($this->clientes_ids[$pessoa->id][$i]);
                        unset($this->resouce_pessoa[$conn->resourceId]);
                        break;
                    }
                }
            }
        }
    }

    public function onError(ConnectionInterface $conn, Exception $exception): void
    {
        $conn->close();
        $this->clients->detach($conn);

        Log::info($exception);
        try {
            if (isset($this->resouce_pessoa[$conn->resourceId])) {
                $pessoa = $this->resouce_pessoa[$conn->resourceId]->pessoa;
                // unset($this->resouce_pessoa[$conn->resourceId]);
                if (($key = array_search($conn->resourceId, $this->enfermagem)) !== false) {
                    unset($this->enfermagem[$key]);
                    // unset($this->clientes_ids[$pessoa->id . 'Enfermagem']);
                    $profissional = Profissional::where('pessoa_id', '=', $pessoa->id)->first();

                    for ($i = 0; $i < count($this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id]); $i++) {
                        if ($this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id][$i]->resourceId == $conn->resourceId) {
                            $this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id][$i]->close();
                            unset($this->clientes_ids[$pessoa->id . 'Enfermagem' . $profissional->empresa_id][$i]);
                            unset($this->resouce_pessoa[$conn->resourceId]);
                            break;
                        }
                    }
                } else if (($key = array_search($conn->resourceId, $this->ti)) !== false) {
                    unset($this->ti[$key]);
                    // unset($this->clientes_ids[$pessoa->id . 'T.I.']);
                    for ($i = 0; $i < count($this->clientes_ids[$pessoa->id . 'T.I.']); $i++) {
                        if ($this->clientes_ids[$pessoa->id . 'T.I.'][$i]->resourceId == $conn->resourceId) {
                            $this->clientes_ids[$pessoa->id . 'T.I.'][$i]->close();
                            unset($this->clientes_ids[$pessoa->id . 'T.I.'][$i]);
                            unset($this->resouce_pessoa[$conn->resourceId]);
                            break;
                        }
                    }
                } else {
                    // unset($this->clientes_ids[$pessoa->id]);
                    for ($i = 0; $i < count($this->clientes_ids[$pessoa->id]); $i++) {
                        if ($this->clientes_ids[$pessoa->id][$i]->resourceId == $conn->resourceId) {
                            $this->clientes_ids[$pessoa->id][$i]->close();
                            unset($this->clientes_ids[$pessoa->id][$i]);
                            unset($this->resouce_pessoa[$conn->resourceId]);
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::info($e);
        }
    }
}
