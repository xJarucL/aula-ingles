@extends('layouts.panel')

@section('title', 'Mis Trabajos')

@section('content')
<div class="panel-dashboard">
    <!-- Header del dashboard -->
    

    <!-- Mensajes de estado -->
    @if (session('success'))
        <div class="message success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9,11 12,14 22,4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="message error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario de subida -->
    <div class="dashboard-card primary" style="margin-bottom: 30px; border: none; box-shadow: none;">
        <div class="card-header">
            <h2>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7,10 12,15 17,10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Subir nuevo archivo
            </h2>
            <p>Agrega documentos PDF, Word y otros archivos de trabajo</p>
        </div>
        <div class="card-actions">
            <form action="{{ route('trabajos.subir') }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <div class="upload-area" style="display: flex; gap: 15px; align-items: center; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
                    <input type="file" name="archivo" accept=".pdf,.doc,.docx" required style="flex: 1; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; background: white;">
                    <button type="submit" class="btn-crear" style="margin: 0; white-space: nowrap;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7,10 12,15 17,10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Subir archivo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de archivos -->
    <div class="dashboard-card secondary" style="border: none; box-shadow: none;">
        <div class="card-header">
            <h2>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
                Archivos guardados
            </h2>
            <p>{{ count($archivos) }} archivo{{ count($archivos) != 1 ? 's' : '' }} disponible{{ count($archivos) != 1 ? 's' : '' }}</p>
        </div>
        <div class="card-actions">
            @forelse ($archivos as $archivo)
                <div class="action-item" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: white; border-radius: 8px; margin-bottom: 10px; border: 1px solid #e0e0e0;">
                    <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                        <span class="action-icon" style="font-size: 1.5rem;">
                            @if(str_ends_with($archivo, '.pdf'))
                                📄
                            @elseif(str_ends_with($archivo, '.doc') || str_ends_with($archivo, '.docx'))
                                📝
                            @else
                                📎
                            @endif
                        </span>
                        <span style="font-weight: 500; color: #333;">{{ $archivo }}</span>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <a href="{{ route('trabajos.descargar', $archivo) }}" class="btn-accion" title="Descargar" style="background: #17a2b8; color: white; width: auto; padding: 8px 12px; height: auto; font-size: 0.85rem; text-decoration: none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7,10 12,15 17,10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Descargar
                        </a>
                        <form action="{{ route('trabajos.eliminar', $archivo) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este archivo?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-accion" title="Eliminar" style="background: #dc3545; color: white; width: auto; padding: 8px 12px; height: auto; font-size: 0.85rem; border: none; cursor: pointer;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3,6 5,6 21,6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: #666; background: #f8f9fa; border-radius: 8px; border: 2px dashed #ddd;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">📁</div>
                    <h3 style="color: #333; margin-bottom: 10px;">No hay archivos aún</h3>
                    <p style="margin: 0; font-size: 1rem;">Sube tu primer archivo usando el formulario de arriba</p>
                </div>
            @endforelse
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
// Mejorar la experiencia del formulario
document.querySelector('input[type="file"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const maxSize = 10; // MB
        if (fileSize > maxSize) {
            alert(`El archivo es muy grande (${fileSize}MB). El tamaño máximo permitido es ${maxSize}MB.`);
            e.target.value = '';
        }
    }
});
</script>
@endpush