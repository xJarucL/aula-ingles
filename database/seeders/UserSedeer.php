<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'jaru',
            'email' => 'jarunyta1096@gmail.com',
            'matricula' => '202200096',
            'rol' => 'alumno',
            'grupo_id' => '1',
            'password' => Hash::make('jaru123')
        ]);
        User::create([
            'name' => 'jarucl',
            'email' => 'jaruny.cl@gmail.com',
            'matricula' => '202100096',
            'rol' => 'admin',
            'grupo_id' => '1',
            'password' => Hash::make('jaru123')
        ]);
    }
}
