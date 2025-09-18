@extends('layouts.panel')

@section('title','Editar Grupo')

@section('content')
<div class="container">
    <h1>Editar Grupo: {{ $grupo->nombre }}</h1>
    <form method="POST" action="{{ route('admin.grupos.update', $grupo->id) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input name="nombre" value="{{ $grupo->nombre }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Código</label>
            <input name="codigo" value="{{ $grupo->codigo }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Profesor</label>
            <select name="profesor_id" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($profesores as $p)
                    <option value="{{ $p->id }}" {{ $grupo->profesor_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Capacidad</label>
            <input name="capacidad_maxima" value="{{ $grupo->capacidad_maxima }}" class="form-control" type="number" min="1">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="activo" class="form-check-input" id="activo" {{ $grupo->activo ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">Activo</label>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>

    <hr>
    <h3>Alumnos en el grupo</h3>
    <form method="POST" action="{{ route('admin.grupos.agregarAlumno', $grupo->id) }}" class="mb-3">
        @csrf
        <div class="input-group">
            <select name="alumno_id" class="form-control">
                <option value="">-- Selecciona alumno --</option>
                @foreach(App\Models\Alumno::orderBy('nombre')->limit(200)->get() as $a)
                    <option value="{{ $a->id }}">{{ $a->nombre }} ({{ $a->matricula }})</option>
                @endforeach
            </select>
            <button class="btn btn-outline-primary">Agregar</button>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.grupos.importar', $grupo->id) }}" enctype="multipart/form-data" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="file" name="archivo" accept=".csv" class="form-control">
            <button class="btn btn-outline-secondary">Importar CSV</button>
        </div>
    </form>

    <table class="table table-sm">
        <thead><tr><th>Alumno</th><th>Matrícula</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($grupo->inscripciones as $ins)
                <tr>
                    <td>{{ $ins->alumno->nombre }}</td>
                    <td>{{ $ins->alumno->matricula }}</td>
                    <td>{{ $ins->estado }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.grupos.quitarAlumno', [$grupo->id, $ins->alumno->id]) }}" style="display:inline">
                            @csrf
                            <button class="btn btn-sm btn-warning">Retirar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
