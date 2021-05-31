<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\ChatWebSocketController;
use App\Http\Controllers\WebSocketController;
use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class WebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         $loop = \React\EventLoop\Factory::create();

         $secure_websockets = new \React\Socket\Server('0.0.0.0:1123', $loop);
         $secure_websockets = new \React\Socket\SecureServer($secure_websockets, $loop, [
             'local_cert' => '/etc/ssl/private/9dfd8ac04f6852d4.crt',
             'local_pk' => '/etc/ssl/private/server.key',
             'verify_peer' => false
         ]);

         $app = new \Ratchet\Http\HttpServer(
             new \Ratchet\WebSocket\WsServer(
                 new ChatWebSocketController()
             )
         );
         $server = new \Ratchet\Server\IoServer($app, $secure_websockets, $loop);
         $server->run();


        //$server = IoServer::factory(
        //    new HttpServer(
        //        new WsServer(
        //            new ChatWebSocketController()
        //        )
        //    ),
        //    1123
        //);
        //$server->run();
    }
}
