<?php

namespace App\Mail;

use App\Models\Anexo2;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudMesaEstudianteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Solicitud de mesa examinadora - Seminarios TUP';
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
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('PDF.anexo2', ['presentacion' => $this->anexo2->presentacion, 'anexo2' => $this->anexo2]);

        return $this->view('emails.mesa_examinadora.solicitud_estudiante')->attachData($pdf->output(), 'Anexo2.pdf');
    }
}
