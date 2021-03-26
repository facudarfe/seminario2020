<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropuestaPasantia extends Model
{
    use HasFactory;

    protected $table = 'propuestas_pasantias';
    protected $fillable = ['titulo', 'descripcion', 'tutores', 'fecha_iniio', 'fecha_fin'];

    public function docente(){
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function alumno(){
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function getCreatedAtAttribute($value){
        return date('d/m/Y', strtotime($value));
    }
}
