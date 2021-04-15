<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropuestaPasantia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'propuestas_pasantias';
    protected $fillable = ['titulo', 'lugar', 'descripcion', 'tutores', 'duracion', 'fecha_fin'];

    public function docente(){
        return $this->belongsTo(User::class, 'docente_id')->withTrashed();
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function getCreatedAtAttribute($value){
        return date('d/m/Y', strtotime($value));
    }

    public function getFechaFinAttribute($value){
        return date('d/m/Y', strtotime($value));
    }

    public function alumnos(){
        return $this->belongsToMany(User::class, 'propuesta_pasantia_user', 'pasantia_id', 'user_id')
        ->withPivot(['ruta_cv'])->withTrashed();
    }
}
