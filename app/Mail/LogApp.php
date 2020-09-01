<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LogApp extends Mailable
{
    use Queueable;
    use SerializesModels;

    private $log;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($log)
    {
        $this->log = $log;
        $this->email = 'suporte@madelife.med.br';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Recuperação de senha MadeLife');
        $this->to($this->email, $this->log);
        return $this->view('mail.LogApp', [
            'log' => $this->log
        ]);
    }
}
