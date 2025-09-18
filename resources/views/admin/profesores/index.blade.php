@extends('layouts.panel')

@section('title', 'Administrar Profesores')

@section('content')
<div class="container">
    <h1>Profesores</h1>
    <table class="table">
        <thead>
            <tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @foreach($profesores as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->rol }}</td>
                    <td><a href="{{ route('admin.profesores.edit', $p->id) }}" class="btn btn-sm btn-primary">Editar</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
