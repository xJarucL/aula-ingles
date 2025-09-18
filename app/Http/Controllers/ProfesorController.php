<?php
// En app/Http/Controllers/ProfesorController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;

class ProfesorController extends Controller
{
    public function panel()
{
    $actividades = Actividad::where('activa', true)
                           ->orderBy('created_at', 'desc')
                           ->limit(6)
                           ->get();
    
    $totalActividades = Actividad::where('activa', true)->count();
    $tareasRecientes = collect();
    $estudiantes = collect();
    
    return view('profesores.panel', compact(
        'actividades',
        'totalActividades', 
        'tareasRecientes',
        'estudiantes'
    ));
}
}