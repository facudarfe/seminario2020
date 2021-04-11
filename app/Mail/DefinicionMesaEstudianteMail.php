<?php

namespace App\Mail;

use App\Models\Anexo2;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefinicionMesaEstudianteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Definición mesa examinadora - Seminarios TUP';
    public $anexo2;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Anexo2 $anexo2)
    {
        $this->anexo2 = $anexo2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.mesa_examinadora.definicion_estudiante');
    }
}
