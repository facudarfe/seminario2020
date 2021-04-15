<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo2 extends Model
{
    use HasFactory;

    protected $table = 'anexos2';

    public function presentacion(){
        return $this->belongsTo(Anexo1::class, 'anexo1_id', 'id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }

    public function getFechaPropuestaAttribute($value){
        return date('d/m/Y H:i', strtotime($value));
    }

    public function getFechaDefinitivaAttribute($value){
        return $value ? date('d/m/Y H:i', strtotime($value)) : null;
    }

    public function tribunal(){
        return $this->belongsToMany(Docente::class, 'tribunales_evaluadores', 'anexo2_id', 'docente_dni')->withPivot(['titular'])
        ->withTrashed();
    }
}
