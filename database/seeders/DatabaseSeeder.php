<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cuatrimestre;
use App\Models\Parcial;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SistemaAlumnosSeeder::class,
            AlumnoPasswordSeeder::class,
        ]);
    }
}