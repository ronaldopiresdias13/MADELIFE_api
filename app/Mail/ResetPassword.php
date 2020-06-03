<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $user ;
    private $senha;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $senha)
    {
        $this->user  = $user ;
        $this->senha = $senha;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Recuperação de senha MadeLife');
        $this->to($this->user->email, $this->user->pessoa->nome);
        return $this->view('mail.ResetPassword', [
            'user' => $this->user, 'senha' => $this->senha
        ]);
    }
}
