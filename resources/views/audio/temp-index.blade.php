@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 60px; text-align: center;">
    <div style="background: white; padding: 40px; border-radius: 18px; box-shadow: 0 8px 32px rgba(30,132,125,0.1); max-width: 600px; margin: 0 auto;">
        <h2 style="color: #1E847D; margin-bottom: 20px;">🎵 Audio y Subtítulos</h2>
        <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">
            Esta funcionalidad estará disponible pronto. Permite subir archivos de audio y crear subtítulos sincronizados.
        </p>
        
        <div style="background: #f8f9fa; padding: 25px; border-radius: 12px; margin-bottom: 30px;">
            <h3 style="color: #1E847D; margin-bottom: 15px; font-size: 1.2rem;">¿Qué podrás hacer?</h3>
            <div style="text-align: left; max-width: 400px; margin: 0 auto;">
                <p style="margin: 10px 0; color: #555;"><strong>📤</strong> Subir archivos de audio (MP3, WAV, etc.)</p>
                <p style="margin: 10px 0; color: #555;"><strong>✏️</strong> Crear subtítulos sincronizados</p>
                <p style="margin: 10px 0; color: #555;"><strong>⏰</strong> Editor con marcado de tiempo preciso</p>
                <p style="margin: 10px 0; color: #555;"><strong>🎬</strong> Reproductor con subtítulos en pantalla</p>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('voice.reconocimiento') }}" 
               style="display: inline-block; padding: 12px 25px; background: #1E847D; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s;">
                🎤 Reconocimiento de Voz
            </a>
            <a href="{{ route('inicio') }}" 
               style="display: inline-block; padding: 12px 25px; background: #666; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s;">
                📝 Volver al Examen
            </a>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #e8f5e9; border-radius: 10px; border-left: 4px solid #1E847D;">
            <p style="margin: 0; color: #1E847D; font-weight: 600;">
                💡 Para activar esta funcionalidad, completa la configuración de la base de datos y ejecuta las migraciones.
            </p>
        </div>
    </div>
</div>
@endsection