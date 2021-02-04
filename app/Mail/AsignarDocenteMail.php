<?php

namespace App\Mail;

use App\Models\Anexo1;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AsignarDocenteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Asignación presentacion - Seminario TUP";
    public $presentacion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Anexo1 $presentacion)
    {
        $this->presentacion = $presentacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.presentaciones.asignarDocente');
    }
}
