<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlumnoPrueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('alumnos')->insert([
            'nombre' => 'Ariel',
            'apellidos' => 'Salazar',
            'matricula' => '202200096',
            'email' => 'jarunyta1096@gmail.com',
            'password' => Hash::make('202200096'),
            'carrera_id' => '1',
            'cuatrimestre_actual' => '10',
            'parcial_actual' => '1',
            'activo' => '1'
        ]);
    }
}
