@extends('layouts.alumno')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
        min-height: 100vh;
    }

    .header {
        background: linear-gradient(135deg, #1e847d 0%, #2a9d8f 100%);
        color: white;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 2rem;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .nav-links {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: background-color 0.3s;
    }

    .nav-links a:hover {
        background: rgba(255,255,255,0.1);
    }

    .login-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.5rem 1.5rem !important;
        border-radius: 20px !important;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .breadcrumb {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .breadcrumb ol {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .breadcrumb li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb li:not(:last-child)::after {
        content: '›';
        color: #666;
        margin-left: 0.5rem;
    }

    .breadcrumb a {
        color: #1e847d;
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb .active {
        color: #666;
        font-weight: 500;
    }

    .parcial-header {
        background: linear-gradient(135deg, #1e847d 0%, #2a9d8f 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    .parcial-header h1 {
        margin: 0 0 1rem 0;
        font-size: 2.5rem;
        font-weight: 600;
    }

    .parcial-info {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 1.5rem;
        font-size: 1.1rem;
        flex-wrap: wrap;
    }

    .info-item {
        background: rgba(255,255,255,0.2);
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }

    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--accent-color);
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-card.total {
        --accent-color: #007bff;
    }

    .stat-card.completed {
        --accent-color: #28a745;
    }

    .stat-card.pending {
        --accent-color: #ffc107;
    }

    .stat-card.average {
        --accent-color: #17a2b8;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--accent-color);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .stat-label {
        color: #666;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    .activities-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .section-title {
        color: #1e847d;
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-controls {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 2px solid #1e847d;
        background: white;
        color: #1e847d;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background: #1e847d;
        color: white;
    }

    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .activity-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        position: relative;
    }

    .activity-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #1e847d;
    }

    .activity-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 1;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-locked {
        background: #f8d7da;
        color: #721c24;
    }

    .activity-image {
        height: 180px;
        background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .activity-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .activity-placeholder {
        font-size: 3rem;
        color: #1e847d;
        opacity: 0.3;
    }

    .activity-content {
        padding: 1.5rem;
    }

    .activity-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
    }

    .activity-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 1rem;
    }

    .activity-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .meta-item {
        background: #f8f9fa;
        padding: 0.75rem 0.5rem;
        border-radius: 8px;
    }

    .meta-value {
        font-weight: bold;
        color: #1e847d;
        font-size: 1.1rem;
        display: block;
    }

    .meta-label {
        font-size: 0.75rem;
        color: #666;
        text-transform: uppercase;
        margin-top: 0.25rem;
        letter-spacing: 0.5px;
    }

    .activity-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        text-align: center;
        font-size: 0.9rem;
        flex: 1;
    }

    .btn-primary {
        background: #1e847d;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-outline-primary {
        background: transparent;
        color: #1e847d;
        border: 1px solid #1e847d;
    }

    .btn-outline-info {
        background: transparent;
        color: #17a2b8;
        border: 1px solid #17a2b8;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        color: white;
        text-decoration: none;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        color: #ccc;
    }

    .empty-state h3 {
        margin-bottom: 1rem;
        color: #333;
    }

    .empty-state p {
        margin-bottom: 2rem;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            padding: 0 1rem;
        }

        .nav-links {
            flex-wrap: wrap;
            justify-content: center;
        }

        .container {
            padding: 1rem;
        }

        .parcial-header {
            padding: 2rem 1.5rem;
        }

        .parcial-header h1 {
            font-size: 2rem;
        }

        .parcial-info {
            flex-direction: column;
            gap: 0.5rem;
        }

        .stats-section {
            grid-template-columns: repeat(2, 1fr);
        }

        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .filter-controls {
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
        }

        .activities-grid {
            grid-template-columns: 1fr;
        }

        .activity-actions {
            flex-direction: column;
        }
    }
</style>



<div class="container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <ol>
            <li>
                <a href="{{ route('alumnos.mis-grupos') }}">
                    <i class="fas fa-home"></i> Mis Grupos
                </a>
            </li>
            @if(isset($cuatrimestre))
                <li>{{ $cuatrimestre->nombre }}</li>
            @endif
            @if(isset($parcial))
                <li class="active">{{ $parcial->nombre }}</li>
            @endif
        </ol>
    </nav>

    <!-- Header del parcial -->
    <div class="parcial-header">
        <h1>
            @if(isset($parcial))
                📚 {{ $parcial->nombre }}
            @else
                📚 Actividades del Parcial
            @endif
        </h1>
        
        <div class="parcial-info">
            @if(isset($cuatrimestre))
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i> {{ $cuatrimestre->nombre }}
                </div>
            @endif
            @if(isset($alumnoData))
                <div class="info-item">
                    <i class="fas fa-user"></i> {{ $alumnoData['nombre'] }}
                </div>
                <div class="info-item">
                    <i class="fas fa-id-card"></i> {{ $alumnoData['matricula'] }}
                </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas -->
    @if(isset($actividades))
        <div class="stats-section">
            <div class="stat-card total">
                <div class="stat-icon">📋</div>
                <div class="stat-number">{{ $actividades->count() }}</div>
                <div class="stat-label">Total Actividades</div>
            </div>
            
            <div class="stat-card completed">
                @php
                    $completadas = isset($intentos) ? $intentos->keys()->count() : 0;
                @endphp
                <div class="stat-icon">✅</div>
                <div class="stat-number">{{ $completadas }}</div>
                <div class="stat-label">Completadas</div>
            </div>
            
            <div class="stat-card pending">
                @php
                    $pendientes = $actividades->count() - $completadas;
                @endphp
                <div class="stat-icon">⏰</div>
                <div class="stat-number">{{ $pendientes }}</div>
                <div class="stat-label">Pendientes</div>
            </div>
            
            <div class="stat-card average">
                @php
                    $promedio = isset($intentos) ? $intentos->flatten()->avg('porcentaje') ?? 0 : 0;
                @endphp
                <div class="stat-icon">📊</div>
                <div class="stat-number">{{ number_format($promedio, 1) }}%</div>
                <div class="stat-label">Promedio</div>
            </div>
        </div>
    @endif

    <!-- Sección de actividades -->
    <div class="activities-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-tasks"></i> Actividades Disponibles
            </h2>
            <div class="filter-controls">
                <button class="filter-btn active" onclick="filterActivities('all')">Todas</button>
                <button class="filter-btn" onclick="filterActivities('completed')">Completadas</button>
                <button class="filter-btn" onclick="filterActivities('pending')">Pendientes</button>
            </div>
        </div>

        @if(isset($actividades) && $actividades->count() > 0)
            <div class="activities-grid" id="activitiesGrid">
                @foreach($actividades as $actividad)
                    @php
                        $intentosActividad = isset($intentos) ? $intentos->get($actividad->id, collect()) : collect();
                        $mejorIntento = $intentosActividad->sortByDesc('porcentaje')->first();
                        $totalIntentos = $intentosActividad->count();
                        $intentosRestantes = $actividad->intentos_permitidos - $totalIntentos;
                        $completada = $totalIntentos > 0;
                        $contenido = $actividad->contenido;
                        $tipoActividad = is_array($contenido) ? ($contenido['tipo'] ?? 'quiz') : 'quiz';
                        
                        // Determinar estado
                        if ($completada) {
                            $estado = $mejorIntento->porcentaje >= 70 ? 'completed' : 'completed';
                            $estadoTexto = $mejorIntento->porcentaje >= 70 ? 'Aprobada' : 'Completada';
                            $estadoClase = $mejorIntento->porcentaje >= 70 ? 'status-completed' : 'status-pending';
                        } else {
                            $estado = 'pending';
                            $estadoTexto = 'Pendiente';
                            $estadoClase = 'status-pending';
                        }
                    @endphp
                    
                    <div class="activity-card" data-status="{{ $estado }}">
                        <div class="activity-status {{ $estadoClase }}">
                            {{ $estadoTexto }}
                        </div>
                        
                        <div class="activity-image">
                            @if($actividad->imagen)
                                <img src="{{ asset('storage/' . $actividad->imagen) }}" alt="{{ $actividad->nombre }}">
                            @else
                                <div class="activity-placeholder">
                                    @switch($tipoActividad)
                                        @case('quiz')
                                            🧠
                                            @break
                                        @case('completar')
                                            ✍️
                                            @break
                                        @case('listening')
                                            🎧
                                            @break
                                        @default
                                            📝
                                    @endswitch
                                </div>
                            @endif
                        </div>
                        
                        <div class="activity-content">
                            <h3 class="activity-title">{{ $actividad->nombre }}</h3>
                            
                            @if($actividad->descripcion)
                                <p class="activity-description">
                                    {{ Str::limit($actividad->descripcion, 100) }}
                                </p>
                            @endif
                            
                            <div class="activity-meta">
                                <div class="meta-item">
                                    <span class="meta-value">{{ $totalIntentos }}</span>
                                    <div class="meta-label">Intentos</div>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-value">{{ $intentosRestantes }}</span>
                                    <div class="meta-label">Restantes</div>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-value">{{ $mejorIntento ? number_format($mejorIntento->porcentaje, 0) . '%' : '-' }}</span>
                                    <div class="meta-label">Mejor</div>
                                </div>
                            </div>
                            
                            <div class="activity-actions">
                                @if($intentosRestantes > 0)
                                    <a href="{{ route('alumnos.ver-actividad', $actividad->id) }}" 
                                       class="btn {{ $completada ? 'btn-outline-primary' : 'btn-primary' }}">
                                        <i class="fas fa-{{ $completada ? 'redo' : 'play' }}"></i> 
                                        {{ $completada ? 'Reintentar' : 'Comenzar' }}
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban"></i> Sin intentos
                                    </button>
                                @endif

                                @if($completada)
                                    <a href="{{ route('alumnos.resultado', $mejorIntento->id) }}" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-chart-bar"></i> Resultado
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>No hay actividades disponibles</h3>
                <p>
                    Actualmente no hay actividades asignadas para este parcial.<br>
                    Contacta a tu profesor para más información.
                </p>
                <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Volver a Mis Grupos
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function filterActivities(filter) {
    // Actualizar botones
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Filtrar actividades
    const activities = document.querySelectorAll('.activity-card');
    
    activities.forEach(activity => {
        const status = activity.getAttribute('data-status');
        let show = false;
        
        switch(filter) {
            case 'all':
                show = true;
                break;
            case 'completed':
                show = status === 'completed';
                break;
            case 'pending':
                show = status === 'pending';
                break;
        }
        
        if (show) {
            activity.style.display = 'block';
            setTimeout(() => {
                activity.style.opacity = '1';
                activity.style.transform = 'scale(1)';
            }, 100);
        } else {
            activity.style.opacity = '0';
            activity.style.transform = 'scale(0.9)';
            setTimeout(() => {
                activity.style.display = 'none';
            }, 300);
        }
    });
}

// Animaciones de entrada
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.activity-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Mostrar alertas
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('success') }}');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('error') }}');
    });
@endif
</script>
@endsection