<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversa;
use App\Models\ConversaMensagem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SplObjectStorage;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class ChatWebSocketController extends Controller implements MessageComponentInterface
{
    private $clients;
    private $clientes_ids = [];
    private $resouce_pessoa = [];

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

                // Log::info($message->token_type . ' ' . $message->token);
                // Log::info($response->getBody());
                // $resp = json_decode($response->getBody());
                // $response->getBody()->close();
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

                $user_id = DB::table('oauth_access_tokens')->where('id', $user_token)->value('user_id');
                $resp = User::where('id','=',$user_id)->with('pessoa')->first();
                if($resp==null){
                    $from->send(json_encode(['type' => 'disconnect', 'mensagem' => 'Usuário inválido ou desconectado']));
                    return;
                }
               

                // if (property_exists($resp, 'message')) {
                //     $from->send(json_encode(['type' => 'disconnect', 'mensagem' => 'Usuário inválido ou desconectado']));
                //     return;
                // }
                if (isset($this->clientes_ids[$resp->pessoa->id])) {
                    array_push($this->clientes_ids[$resp->pessoa->id], $from);
                } else {
                    $this->clientes_ids[$resp->pessoa->id] = [];
                    array_push($this->clientes_ids[$resp->pessoa->id], $from);
                }
                $this->resouce_pessoa[$from->resourceId] = $resp;
                Log::info($from->resourceId);

                Log::info('connect');

                // Log::info($resp->id);
            } catch (Exception $e) {
                Log::error($e);
                $from->send(json_encode(['type' => 'disconnect', 'mensagem' => 'Usuário inválido ou desconectado']));
                return;
            }
        } else if ($message->type == 'msg_receive') {
            Log::info($from->resourceId);

            Log::info($msg);
            $conversa = Conversa::where(function ($q) use ($message) {
                $q->where('sender_id', $message->sender)->where('receive_id', $message->receive);
            })->orWhere(function ($q) use ($message) {
                $q->where('sender_id', $message->receive)->where('receive_id', $message->sender);
            })->first();
            Log::info('reeive1');
            if ($conversa != null) {

                $mensagem = $conversa->mensagens()->where('conversas_mensagens.sender_id', $message->sender)->where('uuid', $message->uuid)->first();
                Log::info('reeive2');

                if ($mensagem != null) {
                    $conversa->mensagens()->where('conversas_mensagens.id', '<=', $mensagem->id)->where('conversas_mensagens.sender_id', $message->sender)->update(['visto' => true]);
                    Log::info('reeive3');

                    if (isset($this->clientes_ids[$message->sender])) {
                        foreach ($this->clientes_ids[$message->sender] as $socket) {

                            $socket->send(json_encode([
                                'receive' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                                'sender' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                                'conversa_id' => $conversa->id,
                                'uuid' => $message->uuid,
                                'type' => 'msg_receive'
                            ]));
                        }
                    }
                }
            }
        } else if ($message->type == 'message') {
            Log::info($from->resourceId);

            Log::info($msg);
            $conversa = Conversa::where(function ($q) use ($message) {
                $q->where('sender_id', $message->sender)->where('receive_id', $message->receive);
            })->orWhere(function ($q) use ($message) {
                $q->where('sender_id', $message->receive)->where('receive_id', $message->sender);
            })->first();
            if ($conversa == null) {
                Log::info(json_encode($this->resouce_pessoa[$from->resourceId]->pessoa->id));
                $conversa = new Conversa();
                $conversa->fill([
                    'sender_id' => $this->resouce_pessoa[$from->resourceId]->pessoa->id,
                    'receive_id' => $message->receive,
                ])->save();
            }
            Log::info('mensagem1.5');

            $mensagem = new ConversaMensagem();
            try {
                Log::info('COnversa id: ' . $conversa->id);
                Log::info('sender_id: ' . $this->resouce_pessoa[$from->resourceId]->pessoa->id);
                Log::info('message: ' . $message->mensagem);


                $mensagem->fill([
                    'conversa_id' => $conversa->id,
                    'sender_id' => $this->resouce_pessoa[$from->resourceId]->pessoa->id,
                    'message' => $message->mensagem,
                    'uuid' => $message->uuid,
                    'visto' => false
                ])->save();
            } catch (Exception $e) {
                Log::error($e);
                Log::info('top');
                return;
            }
            Log::info('mensagem2.1');

            if (isset($this->clientes_ids[$message->receive])) {
                foreach ($this->clientes_ids[$message->receive] as $socket) {

                    $socket->send(json_encode([
                        'sender' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                        'receive' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                        'conversa_id' => $conversa->id,
                        'mensagem' => $message->mensagem,
                        'uuid' => $mensagem->uuid,
                        'type' => 'message',
                        'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

                    ]));
                }
            }
            $from->send(json_encode([
                'sender' => $message->sender,
                'receive' => $message->receive,
                'conversa_id' => $conversa->id,
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
        } else if ($message->type == 'image' || $message->type == 'video') {
            Log::info($from->resourceId);

            Log::info($msg);
            $conversa = Conversa::where(function ($q) use ($message) {
                $q->where('sender_id', $message->sender)->where('receive_id', $message->receive);
            })->orWhere(function ($q) use ($message) {
                $q->where('sender_id', $message->receive)->where('receive_id', $message->sender);
            })->first();
            if ($conversa == null) {
                Log::info(json_encode($this->resouce_pessoa[$from->resourceId]->pessoa->id));
                $conversa = new Conversa();
                $conversa->fill([
                    'sender_id' => $this->resouce_pessoa[$from->resourceId]->pessoa->id,
                    'receive_id' => $message->receive,
                ])->save();
            }
            Log::info('file1.5');

            $mensagem = new ConversaMensagem();
            try {
                Log::info('COnversa id: ' . $conversa->id);
                Log::info('sender_id: ' . $this->resouce_pessoa[$from->resourceId]->pessoa->id);


                $mensagem->fill([
                    'conversa_id' => $conversa->id,
                    'sender_id' => $this->resouce_pessoa[$from->resourceId]->pessoa->id,
                    'arquivo' => $message->arquivo,
                    'type' => $message->type,
                    'uuid' => $message->uuid,
                    'visto' => false
                ])->save();
            } catch (Exception $e) {
                Log::info('top');
                return;
            }
            Log::info('file2.1');

            if (isset($this->clientes_ids[$message->receive])) {
                foreach ($this->clientes_ids[$message->receive] as $socket) {

                    $socket->send(json_encode([
                        'sender' => $this->resouce_pessoa[$from->resourceId]->pessoa,
                        'receive' => $this->resouce_pessoa[$socket->resourceId]->pessoa,
                        'conversa_id' => $conversa->id,
                        'arquivo' => $message->arquivo,
                        'type' => $message->type,
                        'uuid' => $mensagem->uuid,
                        'created_at' => Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s')

                    ]));
                }
            }
            $from->send(json_encode([
                'sender' => $message->sender,
                'receive' => $message->receive,
                'conversa_id' => $conversa->id,
                'arquivo' => $message->arquivo,
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
    }

    public function onError(ConnectionInterface $conn, Exception $exception): void
    {
        $conn->close();
        $this->clients->detach($conn);

    }
}
