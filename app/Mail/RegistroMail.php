<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistroMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Registro web - Seminario TUP";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $rol)
    {
        $this->nombreUsuario = $nombre;
        $this->rol = $rol;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.registro', ['nombreUsuario' => $this->nombreUsuario, 'rol' => $this->rol]);
    }
}
