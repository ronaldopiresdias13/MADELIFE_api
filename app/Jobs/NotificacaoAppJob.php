<?php

namespace App\Jobs;

use App\Models\Pessoa;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificacaoAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title='';
    protected $message='';
    protected $pessoa=null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pessoa $pessoa, $title, $message)
    {
        $this->pessoa=$pessoa;
        $this->title=$title;
        $this->message=$message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $app_id = 'eaf2c99b-f04b-4953-9ee8-fcfcd856c08c';
        $api_id = "Yjk2NjA1ODEtODJjNC00Njk0LTkzY2UtZDIzMGI3ODEyYzZi";
        Log::info($this->pessoa->id);
        $client = new Client();
        $notification_response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
            'headers' => [
                'Authorization' => 'Basic ' . $api_id,
                'Content-Type' => 'application/json',

            ],
            'json' => [
                "app_id" => $app_id,
                "contents" => ["en" => $this->message],
                "headings" => ["en" => $this->title],
                "include_external_user_ids"=> [(string)$this->pessoa->id],
                "icon"=> "https://img.onesignal.com/t/73b9b966-f19e-4410-8b5d-51ebdef4652e.png",
                "android_accent_color" => "00a99e",
                "data"=>['foo'=>'bar']
            ],
            "http_errors" => false
        ]);

        $boleto = json_decode($notification_response->getBody());
        $notification_response->getBody()->close();

    }
}
