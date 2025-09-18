@extends('layouts.panel')

@section('title', 'Gestión de Grupos')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Grupos</h1>
        <a href="{{ route('admin.grupos.create') }}" class="btn btn-primary">Crear grupo</a>
    </div>

    <form class="mb-3">
        <div class="input-group">
            <input name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombre o código">
            <button class="btn btn-outline-secondary">Buscar</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead><tr><th>Nombre</th><th>Código</th><th>Profesor</th><th>Alumnos</th><th>Acciones</th></tr></thead>
        <tbody>
            @foreach($grupos as $g)
                <tr>
                    <td>{{ $g->nombre }}</td>
                    <td>{{ $g->codigo }}</td>
                    <td>{{ $g->profesor?->name }}</td>
                    <td>{{ $g->alumnos_inscritos_count ?? 0 }}</td>
                    <td>
                        <a href="{{ route('admin.grupos.edit', $g->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('admin.grupos.destroy', $g->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar grupo?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $grupos->links() }}
</div>
@endsection
