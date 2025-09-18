@extends('layouts.panel')

@section('title','Crear Grupo')

@section('content')
<div class="container">
    <h1>Crear Grupo</h1>
    <form method="POST" action="{{ route('admin.grupos.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Código</label>
            <input name="codigo" class="form-control">
        </div>
        <div class="mb-3">
            <label>Profesor</label>
            <select name="profesor_id" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($profesores as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Capacidad</label>
            <input name="capacidad_maxima" class="form-control" type="number" min="1">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="activo" class="form-check-input" id="activo">
            <label class="form-check-label" for="activo">Activo</label>
        </div>
        <button class="btn btn-primary">Crear</button>
    </form>
</div>
@endsection
