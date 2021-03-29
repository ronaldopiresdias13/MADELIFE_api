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
