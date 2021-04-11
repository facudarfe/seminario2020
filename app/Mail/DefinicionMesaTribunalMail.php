<?php

namespace App\Mail;

use App\Models\Anexo2;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DefinicionMesaTribunalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject, $anexo2, $titular;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Anexo2 $anexo2, $titular)
    {
        $this->anexo2 = $anexo2;
        $this->titular = $titular;
        $titular ? $this->subject = 'Tribunal titular - Seminarios TUP' : $this->subject = 'Tribunal suplente - Seminarios TUP';
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

        return $this->view('emails.mesa_examinadora.definicion_tribunal')->attachData($pdf->output(), 'Anexo2.pdf')
                ->attach(public_path() . '/' . $urlInforme, ['as' => 'InformeFinal.pdf']);
    }
}
