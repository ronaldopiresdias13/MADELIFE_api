<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOrcamento extends Mailable
{
    use Queueable;
    use SerializesModels;


    private $email;
    private $anexo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $anexo)
    {
        $this->email = $email;
        $this->anexo = $anexo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Envio de Orçamento');
        $this->to($this->email, 'Madelife');
        // $this->attach($this->anexo);
        return $this->view('mail.SendOrcamento');
    }
}
