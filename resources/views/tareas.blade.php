@extends('layouts.panel')

@section('title', 'Tareas')

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

    @if ($errors->any())
        <div class="message error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <ul style="margin: 5px 0 0 25px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Lista de tareas -->
    @if (!$tareas->isEmpty())
        <div class="dashboard-card secondary" style="border: none; box-shadow: none; background: transparent;">
            <div class="card-header">
                <h2>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9,11 12,14 22,4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    Tareas enviadas
                </h2>
                <p>{{ count($tareas) }} tarea{{ count($tareas) != 1 ? 's' : '' }} por revisar</p>
            </div>
            <div class="card-actions" style="flex-direction: column; gap: 20px;">
                @foreach ($tareas as $tarea)
                    <div class="tarea-card-modern" style="background: white; border-radius: 12px; padding: 25px; border: 1px solid #e0e0e0; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 20px;">
                            <!-- Información del estudiante -->
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #1E847D; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        {{ substr($tarea->alumno, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 style="margin: 0; color: #333; font-size: 1.2rem; font-weight: 600;">{{ $tarea->alumno }}</h3>
                                        <div style="display: flex; align-items: center; gap: 6px; color: #666; font-size: 0.9rem; margin-top: 2px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ $tarea->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Archivo -->
                                <div style="margin-bottom: 15px;">
                                    <a href="{{ route('ver.archivo.tarea', $tarea->archivo) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #f8f9fa; padding: 10px 15px; border-radius: 8px; text-decoration: none; color: #1E847D; font-weight: 500; border: 1px solid #e0e0e0; transition: all 0.3s;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                        </svg>
                                        {{ $tarea->archivo }}
                                    </a>
                                </div>

                                @if ($tarea->calificacion)
                                    <div style="background: #e8f5e8; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                            <span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                                                {{ $tarea->calificacion }}/10
                                            </span>
                                            <span style="color: #666; font-size: 0.85rem;">
                                                Calificada el {{ $tarea->fecha_calificacion ? $tarea->fecha_calificacion->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        </div>
                                        @if ($tarea->comentario)
                                            <div style="color: #555; font-style: italic; font-size: 0.95rem;">
                                                "{{ Str::limit($tarea->comentario, 100) }}"
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Panel de calificación -->
                            <div style="min-width: 300px; background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0;">
                                <h4 style="margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px; color: #1E847D; font-size: 1.1rem;">
                                    @if ($tarea->calificacion)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Recalificar tarea
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9,11 12,14 22,4"></polyline>
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                        </svg>
                                        Calificar tarea
                                    @endif
                                </h4>
                                
                                <form action="{{ route('tareas.calificar', $tarea->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                                    @csrf
                                    <div>
                                        <label for="calificacion_{{ $tarea->id }}" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 0.9rem;">Calificación (0-10)</label>
                                        <input type="number" 
                                               id="calificacion_{{ $tarea->id }}" 
                                               name="calificacion" 
                                               value="{{ $tarea->calificacion ?? '' }}"
                                               placeholder="0-10" 
                                               min="0" 
                                               max="10" 
                                               step="0.1" 
                                               required
                                               style="width: 100%; padding: 10px 12px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; transition: all 0.3s;">
                                    </div>

                                    <div>
                                        <label for="comentario_{{ $tarea->id }}" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 0.9rem;">Comentario (opcional)</label>
                                        <textarea id="comentario_{{ $tarea->id }}" 
                                                  name="comentario" 
                                                  placeholder="Escribe tus comentarios para el estudiante..."
                                                  rows="3"
                                                  style="width: 100%; padding: 10px 12px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 0.95rem; transition: all 0.3s; resize: vertical; font-family: inherit;">{{ $tarea->comentario ?? '' }}</textarea>
                                    </div>

                                    <button type="submit" class="btn-crear" style="margin: 0; width: 100%; {{ $tarea->calificacion ? 'background: linear-gradient(135deg, #28a745 0%, #20c997 100%);' : '' }}">
                                        @if ($tarea->calificacion)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Actualizar calificación
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="9,11 12,14 22,4"></polyline>
                                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                            </svg>
                                            Calificar tarea
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Estado vacío -->
        <div class="dashboard-card accent" style="border: none; box-shadow: none; background: transparent;">
            <div style="text-align: center; padding: 60px 20px; color: #666; background: #f8f9fa; border-radius: 12px; border: 2px dashed #ddd;">
                <div style="font-size: 4rem; margin-bottom: 20px; color: #ccc;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10,9 9,9 8,9"></polyline>
                    </svg>
                </div>
                <h3 style="color: #333; margin-bottom: 10px; font-size: 1.8rem;">No hay tareas por revisar</h3>
                <p style="margin: 0; font-size: 1.1rem;">Cuando los estudiantes envíen sus tareas, aparecerán aquí para su revisión.</p>
            </div>
        </div>
    @endif
</div>
@endsection