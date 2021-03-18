<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SplObjectStorage;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class ChatWebSocketController extends Controller implements MessageComponentInterface
{
    private $clients;

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
            try{
            // Log::info('token '.$message->token);
            // Log::info(json_encode(auth('api')->user()));
            
            $http = new Client();
            $response = $http->get(url('/api/auth/user'), [
                'headers' => [
                    'Accept'=>'application/json',
                    'Authorization' => $message->token_type.' '.$message->token,
                ],
                "http_errors" => false
            ]);

                Log::info($message->token_type.' '.$message->token);
                Log::info($response->getBody());
                $resp = json_decode($response->getBody());
                if(property_exists($resp,'message')){
                    $from->send(json_encode(['type'=>'disconnect','mensagem'=>'Usuário inválido ou desconectado']));
                    return;
                }

                // Log::info($resp->id);
            }
            catch(Exception $e){
                Log::error($e);
                $from->send(json_encode(['type'=>'disconnect','mensagem'=>'Usuário inválido ou desconectado']));
                return;

            }
        } else if ($message->type == 'message') {
            foreach ($this->clients as $client) {
                if ($client->resourceId != $from->resourceId) {
                    $client->send($msg);
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $exception): void
    {
        $conn->close();
    }
}
