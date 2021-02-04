<?php

namespace App\Mail;

use App\Models\Version_Anexo1;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorreccionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Corrección presentación - Seminarios TUP";
    public $version;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Version_Anexo1 $version)
    {
        $this->version = $version;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.presentaciones.correccion');
    }
}
