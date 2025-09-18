<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuatrimestre;
use App\Models\Parcial;

class CuatrimestresSeeder extends Seeder
{
    public function run(): void
    {
        $nombresCuatri = [
            'Primer Cuatrimestre',
            'Segundo Cuatrimestre',
            'Tercer Cuatrimestre',
            'Cuarto Cuatrimestre',
            'Quinto Cuatrimestre',
            'Sexto Cuatrimestre',
            'Séptimo Cuatrimestre',
            'Octavo Cuatrimestre',
            'Noveno Cuatrimestre',
        ];

        foreach ($nombresCuatri as $i => $nombre) {
            $cuatri = Cuatrimestre::create([
                'nombre' => $nombre,
                'orden'  => $i + 1,
                'activo' => true,
            ]);

            foreach (['Primer Parcial', 'Segundo Parcial', 'Tercer Parcial'] as $num => $nombreParcial) {
                Parcial::create([
                    'cuatrimestre_id' => $cuatri->id,
                    'nombre'          => $nombreParcial,
                    'numero'          => $num + 1,
                ]);
            }
        }
    }
}
