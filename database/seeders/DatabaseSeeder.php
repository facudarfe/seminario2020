<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Creacion de roles
        Role::insert([
            ['name' => 'Administrador', 'guard_name'=>'web'], 
            ['name' => 'Docente responsable', 'guard_name'=>'web'], 
            ['name' => 'Docente colaborador', 'guard_name'=>'web'], 
            ['name' => 'Estudiante', 'guard_name'=>'web']
        ]);

        // Crear al Administrador
        $admin = User::create([
            'dni' => '39679617',
            'name' => 'Facundo Darfe',
            'email' => 'facudarfe@gmail.com',
            'password' => Hash::make('13579darfe2468'),
            'direccion' => 'Enrique Arana 1985',
            'telefono' => '3874126303'
        ]);
        $admin->assignRole('Administrador');

        // Creacion de los permisos
        Permission::insert([
            ['name' => 'anexos1.generarPDF', 'guard_name'=>'web'],
            ['name' => 'anexos2.definirFechaYTribunal', 'guard_name'=>'web'],
            ['name' => 'anexos2.evaluar', 'guard_name'=>'web'],
            ['name' => 'anexos2.ver', 'guard_name'=>'web'],
            ['name' => 'contactar.usuario', 'guard_name'=>'web'],
            ['name' => 'docentes.gestionar', 'guard_name'=>'web'],
            ['name' => 'permisos.ver', 'guard_name'=>'web'],
            ['name' => 'presentaciones.asignar.evaluador', 'guard_name'=>'web'],
            ['name' => 'presentaciones.corregir', 'guard_name'=>'web'],
            ['name' => 'presentaciones.regularizar', 'guard_name'=>'web'],
            ['name' => 'propuestas.pasantias.crear', 'guard_name'=>'web'],
            ['name' => 'propuestas.pasantias.generarPDF', 'guard_name'=>'web'],
            ['name' => 'propuestas.pasantias.solicitar', 'guard_name'=>'web'],
            ['name' => 'propuestas.temas.crear', 'guard_name'=>'web'],
            ['name' => 'propuestas.temas.solicitar', 'guard_name'=>'web'],
            ['name' => 'usuarios.crear', 'guard_name'=>'web'],
            ['name' => 'usuarios.editar', 'guard_name'=>'web'],
            ['name' => 'usuarios.editar.password', 'guard_name'=>'web'],
            ['name' => 'usuarios.eliminar', 'guard_name'=>'web'],
            ['name' => 'usuarios.ver', 'guard_name'=>'web'],
        ]);

        // Sincronizar permisos con los roles
        Role::where('name', 'Docente responsable')->first()->syncPermissions([
            'anexos1.generarPDF',
            'anexos2.definirFechaYTribunal',
            'anexos2.evaluar',
            'anexos2.ver',
            'contactar.usuario',
            'presentaciones.asignar.evaluador',
            'presentaciones.corregir',
            'presentaciones.regularizar',
            'propuestas.pasantias.crear',
            'propuestas.pasantias.generarPDF',
            'propuestas.temas.crear',
            'usuarios.crear',
            'usuarios.ver'
        ]);

        Role::where('name', 'Docente colaborador')->first()->syncPermissions([
            'anexos1.generarPDF',
            'presentaciones.corregir',
            'usuarios.crear',
            'usuarios.ver'
        ]);

        Role::where('name', 'Estudiante')->first()->syncPermissions([
            'anexos1.generarPDF',
            'anexos2.ver',
            'propuestas.pasantias.solicitar',
            'propuestas.temas.solicitar'
        ]);
    }
}
