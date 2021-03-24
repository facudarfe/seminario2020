<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropuestaTema extends Model
{
    use HasFactory;

    protected $table = 'propuestas_temas';
    protected $fillable = ['titulo', 'descripcion', 'tecnologias'];

    public function estado(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function docente(){
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function getCreatedAtAttribute($value){
        return date('d/m/Y', strtotime($value));
    }
}
