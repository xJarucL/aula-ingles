@extends('layouts.panel')

@section('title', 'Panel')

@section('content')
<div class="panel-dashboard">
    <!-- Header del dashboard -->
    <div class="dashboard-header">
        <div class="dashboard-title">
            <h1>¡Bienvenido a tu panel docente!</h1>
            <p>Gestiona tus clases, estudiantes y contenido educativo</p>
        </div>
        <div class="dashboard-user">
                <div class="user-info">
                    <span class="user-name">@auth{{ Auth::user()->name }}@else Profesor Invitado @endauth</span>
                </div>
                <div class="user-info">
                    {{-- Mostrar rol real si existe; por defecto 'Profesor' --}}
                    <span class="user-role">@auth{{ Auth::user()->rol ?? 'Profesor' }}@else Profesor @endauth</span>
                </div>
                @auth
                    @if((Auth::user()->rol ?? '') === 'admin')
                        <div style="margin-left:16px;">
                            <a href="{{ Route::has('admin.profesores.index') ? route('admin.profesores.index') : url('/admin/profesores') }}" target="_blank" class="btn btn-sm btn-outline-secondary">Ir a administrar profesores</a>
                        </div>
                    @endif
                @endauth
            </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>983</h3>
                <p>Estudiantes activos</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9,11 12,14 22,4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>12</h3>
                <p>Tareas pendientes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14,2 14,8 20,8"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <h3>8</h3>
                <p>Trabajos creados</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>3</h3>
                <p>Notificaciones</p>
            </div>
        </div>
    </div>

    <!-- Tarjetas principales -->
    <div class="dashboard-cards">
        <div class="dashboard-card primary">
            <div class="card-header">
                <h2>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Crear contenido
                </h2>
                <p>Desarrolla nuevo material educativo</p>
            </div>
            <div class="card-actions">
                {{-- Quitar/ocultar botones sin funcionalidad real: reemplazar # por rutas si se implementan --}}
                <a href="{{ route('trabajos.index') }}" class="action-item">
                    <span class="action-icon">📄</span>
                    <span>Hojas de trabajo</span>
                </a>
                
            </div>
        </div>

        <div class="dashboard-card secondary">
            <div class="card-header">
                <h2>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                    Revisar y evaluar
                </h2>
                <p>Gestiona y evalua actividades</p>
            </div>
            <div class="card-actions">
                <a href="{{ route('profesores.actividades') }}" class="action-item">
                    <span class="action-icon">✅</span>
                    <span>Ver actividades</span>
                </a>
            </div>
        </div>

        <div class="dashboard-card accent">
            <div class="card-header">
                <h2>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Comunicar
                </h2>
                <p>Mantente conectado con tus estudiantes</p>
            </div>
            <div class="card-actions">
                <a href="{{ route('notificaciones') }}" class="action-item">
                    <span class="action-icon">🔔</span>
                    <span>Notificaciones</span>
                </a>
                <a href="{{ route('chat.usuarios') }}" class="action-item">
                    <span class="action-icon">💬</span>
                    <span>Chat privado</span>
                </a>
                <a href="{{ route('chat.grupal') }}" class="action-item">
                    <span class="action-icon">💬</span>
                    <span>Chat grupal</span>
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Botón para mensaje al coordinador (abre modal) -->
@if(auth()->check())
        <div style="position:fixed; right:20px; bottom:80px; z-index:40;">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEnviarAdmin">Mensaje al coordinador</button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalEnviarAdmin" tabindex="-1" aria-labelledby="modalEnviarAdminLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEnviarAdminLabel">Mensaje al coordinador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('profesores.components.enviar_admin_form')
                    </div>
                </div>
            </div>
        </div>
@endif

@push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection