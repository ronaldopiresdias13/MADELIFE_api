<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\ChamadoWebSocketController;
use Illuminate\Console\Command;

class ChamadoWebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chamado:init';

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
         // $loop = \React\EventLoop\Factory::create();

        // $secure_websockets = new \React\Socket\Server('0.0.0.0:10990', $loop);
        // $secure_websockets = new \React\Socket\SecureServer($secure_websockets, $loop, [
        //     'local_cert' => '/etc/letsencrypt/live/madelife.enterscience.com.br/fullchain.pem',
        //     'local_pk' => '/etc/letsencrypt/live/madelife.enterscience.com.br/privkey.pem',
        //     'verify_peer' => false
        // ]);

        // $app = new \Ratchet\Http\HttpServer(
        //     new \Ratchet\WebSocket\WsServer(
        //         new ChamadoWebSocketController()
        //     )
        // );
        // $server = new \Ratchet\Server\IoServer($app, $secure_websockets, $loop);
        // $server->run();

        $loop_ = \React\EventLoop\Factory::create();
        $socket_ = new \React\Socket\Server('0.0.0.0:10990', $loop_);
        $app_ = new \Ratchet\Http\HttpServer(
            new \Ratchet\WebSocket\WsServer(
                new ChamadoWebSocketController()
            )
        );
        $server_ = new \Ratchet\Server\IoServer($app_, $socket_, $loop_);
        $server_->run();
    }
}
