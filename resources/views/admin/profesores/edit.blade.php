@extends('layouts.panel')

@section('title', 'Editar Profesor')

@section('content')
<div class="container">
    <h1>Editar Profesor</h1>
    <form method="POST" action="{{ route('admin.profesores.update', $profesor->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ $profesor->name }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $profesor->email }}">
        </div>
        <div class="mb-3">
            <label>Asignar grupos</label>
            <select name="grupos[]" multiple class="form-control">
                @foreach($grupos as $g)
                    <option value="{{ $g->id }}" {{ $g->profesor_id == $profesor->id ? 'selected' : '' }}>{{ $g->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Promover grupos</label>
            <div>
                @foreach($grupos as $g)
                    <div style="display:flex; align-items:center; margin-bottom:6px;">
                        <div style="flex:1;">{{ $g->nombre }} @if($g->profesor_id) <small>(Asignado)</small> @endif</div>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="promoverGrupo({{ $g->id }}, this)">Promover</button>
                    </div>
                @endforeach
            </div>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>
@push('scripts')
<script>
    async function promoverGrupo(id, btn) {
        if (!confirm('Confirmar promover este grupo al siguiente cuatrimestre?')) return;
        btn.disabled = true;
        try {
            const res = await fetch('{{ url('/admin') }}/grupos/' + id + '/promover', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();
            if (json.success) {
                alert('Grupo promovido');
            } else {
                alert(json.message || 'Error al promover');
            }
        } catch (e) {
            alert('Error de comunicación');
        } finally {
            btn.disabled = false;
        }
    }
</script>
@endpush
@endsection
