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

    public function getCreatedAtAttribute($value){
        return date('d/m/Y', strtotime($value));
    }

    public function versiones(){
        return $this->hasMany(Version_Anexo1::class, 'anexo_id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function alumnos(){
        return $this->belongsToMany(User::class, 'anexo1_user', 'anexo1_id', 'alumno_id')->withPivot(['aceptado'])
        ->where('anexo1_user.aceptado', true)->withTrashed();
    }

    public function alumnosPendientes(){
        return $this->belongsToMany(User::class, 'anexo1_user', 'anexo1_id', 'alumno_id')->withPivot(['aceptado'])
        ->where('anexo1_user.aceptado', false)->withTrashed();
    }

    public function director(){
        return $this->belongsTo(Docente::class, 'director_dni')->withTrashed();
    }

    public function codirector(){
        return $this->belongsTo(Docente::class, 'codirector_dni')->withTrashed();
    }

    public function modalidad(){
        return $this->belongsTo(Modalidad::class, 'modalidad_id');
    }

    public function evaluador(){
        return $this->belongsTo(User::class, 'docente_id')->withTrashed();
    }

    public function anexos2(){
        return $this->hasMany(Anexo2::class, 'anexo1_id');
    }
}
