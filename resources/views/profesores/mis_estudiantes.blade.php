
@extends('layouts.panel')

@section('title', 'Mis Estudiantes')

@section('content')
<div class="container">
    <h2 style="color:#1E847D; font-family: Montserrat, Verdana, Helvetica, sans-serif; font-size:2.2rem; text-align:center; margin-bottom: 40px; margin-top: 30px; letter-spacing:1px;">
        Selecciona una carrera
    </h2>

    <!-- Grid de carreras (enlaces de ejemplo, reemplaza por rutas reales cuando existan) -->
    <div class="carreras-container">
        <div class="carreras-grid">
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card contaduria">
                <span>Contaduría</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card agricultura">
                <span>Agricultura</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card enfermeria">
                <span>Enfermería</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card gastronomia">
                <span>Gastronomía</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card mantenimiento">
                <span>Mantenimiento</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card mecatronica">
                <span>Mecatrónica</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card alimentos">
                <span>Alimentos</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card tecnologias">
                <span>Tecnologías</span>
            </a>
            <a href="{{ route('profesores.estudiantes') }}" class="carrera-card turismo">
                <span>Turismo</span>
            </a>
        </div>
    </div>
</div>
@endsection