<?php

namespace App\Mail;

use App\Models\Anexo2;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class SolicitudMesaDocenteMail extends Mailable
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

        $urlInforme = Storage::url($this->anexo2->ruta_informe);

        return $this->view('emails.mesa_examinadora.solicitud_docente')->attachData($pdf->output(), 'Anexo2.pdf')
                ->attach(public_path() . '/' . $urlInforme, ['as' => 'InformeFinal.pdf']);
    }
}
