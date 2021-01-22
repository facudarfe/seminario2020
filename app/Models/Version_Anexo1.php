<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version_Anexo1 extends Model
{
    use HasFactory;

    protected $table = 'versiones_anexos1';

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function anexo(){
        return $this->belongsTo(Anexo1::class, 'anexo_id');
    }
}
