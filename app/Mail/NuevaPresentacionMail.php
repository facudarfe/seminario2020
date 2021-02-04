<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevaPresentacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Nueva presentación - Seminario TUP';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.presentaciones.nueva', ['titulo' => $this->titulo]);
    }
}
