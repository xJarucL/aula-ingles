@extends('layouts.app')

@php
    $title = 'Lista de Audios';
@endphp

@section('content')
<div class="container" style="max-width: 1200px; margin: 60px auto; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #1E847D; margin: 0;">Lista de Audios con Subtítulos</h2>
        <a href="{{ route('audio.create') }}" class="login-btn" style="margin: 0;">
            Subir Nuevo Audio
        </a>
    </div>

    @if(isset($audioFiles) && (is_countable($audioFiles) ? count($audioFiles) > 0 : $audioFiles->count() > 0))
        <div style="display: grid; gap: 25px;">
            @foreach($audioFiles as $audioFile)
                <div style="background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(30,132,125,0.1); transition: all 0.3s ease;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div style="flex: 1;">
                            <h3 style="color: #1E847D; margin: 0 0 8px 0; font-size: 1.3rem;">{{ $audioFile->title }}</h3>
                            <p style="color: #666; margin: 0 0 10px 0;">{{ $audioFile->original_name }}</p>
                            <div style="display: flex; gap: 15px; font-size: 0.9rem; color: #999;">
                                <span>📅 {{ $audioFile->created_at->format('d/m/Y H:i') }}</span>
                                <span>📊 {{ $audioFile->subtitles->count() }} subtítulos</span>
                                <span>💾 {{ number_format($audioFile->file_size / 1024, 1) }} KB</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('audio.show', $audioFile) }}" 
                               style="padding: 8px 16px; background: #1E847D; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: all 0.3s;">
                                Ver Audio
                            </a>
                            <a href="{{ route('audio.editor', $audioFile) }}" 
                               style="padding: 8px 16px; background: #2CA6A4; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: all 0.3s;">
                                Editar Subtítulos
                            </a>
                            <form method="POST" action="{{ route('audio.destroy', $audioFile) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este audio?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                        <audio controls style="width: 100%;">
                            <source src="{{ $audioFile->file_url }}" type="{{ $audioFile->mime_type }}">
                            Tu navegador no soporta el elemento audio.
                        </audio>
                    </div>

                    @if($audioFile->subtitles->count() > 0)
                        <div style="margin-top: 15px;">
                            <h4 style="color: #1E847D; margin: 0 0 10px 0; font-size: 1rem;">Subtítulos:</h4>
                            <div style="max-height: 100px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 8px;">
                                @foreach($audioFile->subtitles->take(3) as $subtitle)
                                    <div style="font-size: 0.85rem; margin-bottom: 5px; color: #666;">
                                        <strong>{{ gmdate('i:s', $subtitle->start_time) }}</strong> - {{ $subtitle->text }}
                                    </div>
                                @endforeach
                                @if($audioFile->subtitles->count() > 3)
                                    <div style="font-size: 0.8rem; color: #999; font-style: italic;">
                                        ... y {{ $audioFile->subtitles->count() - 3 }} más
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if(method_exists($audioFiles, 'links'))
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $audioFiles->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon" style="font-size: 4rem;">🎵</div>
            <h3>No hay audios disponibles</h3>
            <p>Sube tu primer archivo de audio para comenzar a agregar subtítulos.</p>
            <a href="{{ route('audio.create') }}" class="login-btn" style="margin-top: 20px;">
                Subir Audio
            </a>
        </div>
    @endif
</div>

@if(session('success'))
    <div style="position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 15px 20px; border-radius: 8px; z-index: 1000;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="position: fixed; top: 20px; right: 20px; background: #f44336; color: white; padding: 15px 20px; border-radius: 8px; z-index: 1000;">
        {{ session('error') }}
    </div>
@endif
@endsection