<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, CanResetPassword;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'name',
        'email',
        'password',
        'lu',
        'direccion',
        'telefono'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determina que roles puede crear un usuario con cierto rol
     */
    public function rolesPermitidos(){
        switch($this->getRoleNames()->first()){
            case "Administrador":
                $roles = Role::all();
                break;
            case "Docente responsable":
                $roles = Role::whereNotIn('name', ['Administrador'])->get();
                break;
            case "Docente colaborador":
                $roles = Role::whereNotIn('name', ['Administrador', 'Docente responsable'])->get();
                break;
            default:
                $roles = [];
        }
        return $roles;
    }

    public function presentaciones(){
        return $this->belongsToMany(Anexo1::class, 'anexo1_user', 'alumno_id', 'anexo1_id')->withPivot(['aceptado']);
    }

    public function propuestaTema(){
        return $this->hasOne(PropuestaTema::class, 'alumno_id');
    }

    public function propuestaPasantia(){
        return $this->hasOne(PropuestaPasantia::class, 'alumno_id');
    }

    public function propuestasPasantias(){
        return $this->belongsToMany(PropuestaPasantia::class, 'propuesta_pasantia_user', 'user_id', 'pasantia_id')
        ->withPivot(['ruta_cv']);
    }
}
