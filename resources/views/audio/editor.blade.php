@extends('layouts.app')

@php
    $title = 'Editor de Subtítulos - ' . $audioFile->title;
@endphp

@section('content')
<div style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">
    <!-- Header -->
    <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h2 style="color: #1E847D; margin: 0;">Editor de Subtítulos</h2>
        <p style="color: #666; margin: 5px 0 0 0;">{{ $audioFile->title }}</p>
    </div>

    <!-- Audio Player -->
    <div class="audio-player-container">
        <audio id="audio-player" controls>
            <source src="{{ $audioFile->file_url }}" type="{{ $audioFile->mime_type }}">
            Tu navegador no soporta el elemento audio.
        </audio>
        <div id="current-time" style="text-align: center; margin-top: 10px; color: #666; font-weight: 500;"></div>
    </div>

    <!-- Editor Grid -->
    <div class="subtitle-editor-container">
        <!-- Lista de Subtítulos -->
        <div class="subtitles-list">
            <h3 style="color: #1E847D; margin: 0 0 15px 0;">Lista de Subtítulos</h3>
            <div id="subtitles-container">
                <div class="empty-subtitles">
                    <div class="icon">🎵</div>
                    <p>No hay subtítulos aún. Comienza agregando el primero.</p>
                </div>
            </div>
        </div>

        <!-- Panel de Edición -->
        <div class="edit-panel">
            <h3>Agregar/Editar Subtítulo</h3>
            
            <div class="time-controls">
                <button type="button" class="time-btn" onclick="marcarTiempo('inicio')">
                    Marcar Inicio
                    <span id="tiempo-inicio">--:--</span>
                </button>
                <button type="button" class="time-btn" onclick="marcarTiempo('fin')">
                    Marcar Fin
                    <span id="tiempo-fin">--:--</span>
                </button>
            </div>

            <textarea id="texto-subtitulo" 
                     class="subtitle-input"
                     placeholder="Escribe el texto del subtítulo..."></textarea>

            <button type="button" class="save-btn" onclick="guardarSubtitulo()">
                Guardar Subtítulo
            </button>

            <div style="margin-top: 15px; text-align: center;">
                <a href="{{ route('audio.show', $audioFile) }}" 
                   style="color: #1E847D; text-decoration: none; font-weight: 500;">
                    ← Ver Audio con Subtítulos
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Overlay para subtítulos -->
<div id="subtitle-overlay" class="subtitle-overlay"></div>

<script>
let subtitles = [];
let tiempoInicio = null;
let tiempoFin = null;
let editingIndex = -1;
let audioPlayer;

// Configuración CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener("DOMContentLoaded", () => {
    audioPlayer = document.getElementById("audio-player");
    cargarSubtitulos();
    
    // Actualizar tiempo actual
    audioPlayer.addEventListener("timeupdate", (e) => {
        document.getElementById("current-time").textContent = 
            'Tiempo actual: ' + formatTime(e.target.currentTime);
        
        // Mostrar subtítulos
        mostrarSubtitulo(e.target.currentTime);
    });
});

async function cargarSubtitulos() {
    try {
        const response = await fetch(`/audio/{{ $audioFile->id }}/subtitles`);
        const data = await response.json();
        subtitles = data;
        actualizarListaSubtitulos();
    } catch (error) {
        console.error('Error cargando subtítulos:', error);
        mostrarNotificacion('Error al cargar subtítulos', 'error');
    }
}

function actualizarListaSubtitulos() {
    const container = document.getElementById("subtitles-container");
    
    if (subtitles.length === 0) {
        container.innerHTML = `
            <div class="empty-subtitles">
                <div class="icon">🎵</div>
                <p>No hay subtítulos aún. Comienza agregando el primero.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = subtitles
        .sort((a, b) => a.start_time - b.start_time)
        .map((sub, index) => `
            <div class="subtitle-item ${editingIndex === index ? 'editing' : ''}" data-index="${index}">
                <div class="subtitle-time">${formatTime(sub.start_time)} - ${formatTime(sub.end_time)}</div>
                <div class="subtitle-text">${sub.text}</div>
                <div class="subtitle-actions">
                    <button class="subtitle-btn edit" onclick="editarSubtitulo(${index})">
                        Editar
                    </button>
                    <button class="subtitle-btn delete" onclick="eliminarSubtitulo(${index})">
                        Eliminar
                    </button>
                </div>
            </div>
        `).join("");
}

function marcarTiempo(tipo) {
    const tiempo = audioPlayer.currentTime;
    
    if (tipo === 'inicio') {
        tiempoInicio = tiempo;
        document.getElementById('tiempo-inicio').textContent = formatTime(tiempoInicio);
    } else {
        tiempoFin = tiempo;
        document.getElementById('tiempo-fin').textContent = formatTime(tiempoFin);
    }
}

async function guardarSubtitulo() {
    const texto = document.getElementById('texto-subtitulo').value.trim();
    
    if (!texto || tiempoInicio === null || tiempoFin === null) {
        mostrarNotificacion('Completa todos los campos', 'warning');
        return;
    }
    
    if (tiempoFin <= tiempoInicio) {
        mostrarNotificacion('El tiempo de fin debe ser mayor al de inicio', 'warning');
        return;
    }

    const subtitleData = {
        start_time: tiempoInicio,
        end_time: tiempoFin,
        text: texto
    };

    try {
        let response;
        
        if (editingIndex >= 0) {
            // Actualizar subtítulo existente
            const subtitleId = subtitles[editingIndex].id;
            response = await fetch(`/audio/{{ $audioFile->id }}/subtitles/${subtitleId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(subtitleData)
            });
        } else {
            // Crear nuevo subtítulo
            response = await fetch(`/audio/{{ $audioFile->id }}/subtitles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(subtitleData)
            });
        }

        if (response.ok) {
            const subtitle = await response.json();
            
            if (editingIndex >= 0) {
                subtitles[editingIndex] = subtitle;
                mostrarNotificacion('Subtítulo actualizado', 'success');
            } else {
                subtitles.push(subtitle);
                mostrarNotificacion('Subtítulo guardado', 'success');
            }
            
            actualizarListaSubtitulos();
            limpiarEditor();
        } else {
            const errorData = await response.json();
            mostrarNotificacion('Error al guardar: ' + (errorData.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión', 'error');
    }
}

function limpiarEditor() {
    editingIndex = -1;
    tiempoInicio = null;
    tiempoFin = null;
    document.getElementById('texto-subtitulo').value = '';
    document.getElementById('tiempo-inicio').textContent = '--:--';
    document.getElementById('tiempo-fin').textContent = '--:--';
}

function editarSubtitulo(index) {
    const subtitle = subtitles[index];
    
    editingIndex = index;
    tiempoInicio = subtitle.start_time;
    tiempoFin = subtitle.end_time;
    document.getElementById('texto-subtitulo').value = subtitle.text;
    document.getElementById('tiempo-inicio').textContent = formatTime(subtitle.start_time);
    document.getElementById('tiempo-fin').textContent = formatTime(subtitle.end_time);
    
    actualizarListaSubtitulos();
    
    // Scroll al panel de edición en móvil
    if (window.innerWidth <= 768) {
        document.querySelector('.edit-panel').scrollIntoView({ behavior: 'smooth' });
    }
}

async function eliminarSubtitulo(index) {
    if (!confirm('¿Seguro que deseas eliminar este subtítulo?')) {
        return;
    }

    const subtitleId = subtitles[index].id;
    
    try {
        const response = await fetch(`/audio/{{ $audioFile->id }}/subtitles/${subtitleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (response.ok) {
            subtitles.splice(index, 1);
            mostrarNotificacion('Subtítulo eliminado', 'success');
            actualizarListaSubtitulos();
            
            if (editingIndex === index) {
                limpiarEditor();
            }
        } else {
            mostrarNotificacion('Error al eliminar subtítulo', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión', 'error');
    }
}

function mostrarSubtitulo(currentTime) {
    const subtitle = subtitles.find(sub => currentTime >= sub.start_time && currentTime <= sub.end_time);
    const overlay = document.getElementById('subtitle-overlay');
    
    if (subtitle) {
        overlay.textContent = subtitle.text;
        overlay.classList.add('show');
    } else {
        overlay.classList.remove('show');
    }
}

function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    const ms = Math.floor((seconds % 1) * 1000);
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}.${String(ms).padStart(3, '0')}`;
}

function mostrarNotificacion(mensaje, tipo) {
    // Eliminar notificación anterior si existe
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `notification ${tipo}`;
    notification.textContent = mensaje;
    
    document.body.appendChild(notification);
    
    // Mostrar notificación
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection