<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo1 extends Model
{
    use HasFactory;

    protected $table = 'anexos1';

    public function getFechaAttribute($value){
        return date('d/m/Y', strtotime($value));
    }

    public function versiones(){
        return $this->hasMany(Version_Anexo1::class, 'anexo_id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function alumno(){
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function director(){
        return $this->belongsTo(User::class, 'director_id');
    }

    public function codirector(){
        return $this->belongsTo(User::class, 'codirector_id');
    }

    public function modalidad(){
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }

    public function evaluador(){
        return $this->belongsTo(User::class, 'docente_id');
    }
}
