<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AlumnoPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder will assign a random password to each alumno that has no password set.
     * It will save a CSV file in storage/app with the plaintext passwords so the admin can distribute them.
     */
    public function run()
    {
        $alumnos = Alumno::whereNull('password')
                    ->orWhere('password', '')
                    ->get();

        if ($alumnos->isEmpty()) {
            $this->command->info('No hay alumnos sin contraseña.');
            return;
        }

        $lines = ["matricula,password"];

        foreach ($alumnos as $alumno) {
            $plain = Str::random(10);
            $alumno->password = Hash::make($plain);
            $alumno->save();

            $lines[] = "{$alumno->matricula},{$plain}";
            $this->command->info("Contraseña generada para: {$alumno->matricula}");
        }

        $fileName = 'alumno_passwords_' . date('Ymd_His') . '.csv';
        $path = storage_path('app/' . $fileName);
        @mkdir(dirname($path), 0755, true);
        file_put_contents($path, implode(PHP_EOL, $lines));

        $this->command->info("Archivo generado: {$path}");
    }
}
