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


    private $emails;
    private $anexo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emails, $anexo)
    {
        $this->emails  = $emails;
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
        $this->to('romario.pires@grupolife.med.br');
        // $this->attach($this->anexo);
        return $this->view('mail.SendOrcamento');
        // return $this->view('view.name');
    }
}
