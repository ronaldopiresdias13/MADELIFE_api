<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOrcamento extends Mailable
{
    use Queueable;
    use SerializesModels;


    private $file;
    private $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, $name)
    {
        $this->name = $name;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('comercial@madelife.med.br', 'MadeLife Gestão em Saúde')
            // ->from('teste@madelife.med.br')
            // ->to($this->email)
            ->view('mail.SendOrcamento')
            ->attachData($this->file, 'orcamento.pdf', ['mime' => 'application/pdf',]);


        // $this->subject('Envio de Orçamento');
        // $this->to($this->email, 'Madelife');
        // $this->attach($this->anexo);
        // return $this->view('mail.SendOrcamento');
    }
}
