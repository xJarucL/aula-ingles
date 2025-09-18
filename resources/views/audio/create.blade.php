@extends('layouts.app')

@php
    $title = 'Subir Audio';
@endphp

@section('content')
<div class="container" style="max-width: 600px; margin: 80px auto; padding: 0 20px;">
    <div class="student-form">
        <h3>Subir Archivo de Audio</h3>
        <p>Selecciona un archivo de audio para agregar subtítulos</p>

        <form method="POST" action="{{ route('audio.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="input-group">
                <label for="title">Título del Audio</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       placeholder="Ingresa un título descriptivo"
                       required>
            </div>

            <div class="input-group">
                <label for="audio_file">Archivo de Audio</label>
                <div style="position: relative;">
                    <input type="file" 
                           id="audio_file" 
                           name="audio_file" 
                           accept=".mp3,.wav,.ogg,.m4a" 
                           required
                           style="position: absolute; left: -9999px; opacity: 0;">
                    
                    <label for="audio_file" 
                           style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 20px; border: 2px dashed #1E847D; border-radius: 12px; background: #f8f9fa; color: #1E847D; cursor: pointer; transition: all 0.3s ease; font-weight: 500;">
                        <span style="font-size: 1.5rem;">🎵</span>
                        <span id="file-label">Seleccionar archivo de audio</span>
                    </label>
                </div>
                <div style="font-size: 0.85rem; color: #666; margin-top: 8px;">
                    Formatos soportados: MP3, WAV, OGG, M4A (Máximo 20MB)
                </div>
            </div>

            <button type="submit" class="student-submit-btn">
                Subir Audio
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('audio.index') }}" 
               style="color: #1E847D; text-decoration: none; font-weight: 500;">
                ← Volver a la lista de audios
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('audio_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const label = document.getElementById('file-label');
    const fileInput = document.querySelector('label[for="audio_file"]');
    
    if (file) {
        label.textContent = file.name;
        fileInput.style.background = '#e8f5e9';
        fileInput.style.borderColor = '#4caf50';
    } else {
        label.textContent = 'Seleccionar archivo de audio';
        fileInput.style.background = '#f8f9fa';
        fileInput.style.borderColor = '#1E847D';
    }
});

// Validación de tamaño de archivo
document.querySelector('form').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('audio_file');
    const file = fileInput.files[0];
    
    if (file && file.size > 20 * 1024 * 1024) { // 20MB
        e.preventDefault();
        alert('El archivo es demasiado grande. El tamaño máximo es 20MB.');
        return false;
    }
});
</script>
@endsection