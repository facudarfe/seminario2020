<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropuestaTema extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'propuestas_temas';
    protected $fillable = ['titulo', 'descripcion', 'tecnologias'];

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function docente(){
        return $this->belongsTo(User::class, 'docente_id')->withTrashed();
    }

    public function alumno(){
        return $this->belongsTo(User::class, 'alumno_id')->withTrashed();
    }

    public function getCreatedAtAttribute($value){
        return date('d/m/Y', strtotime($value));
    }
}
