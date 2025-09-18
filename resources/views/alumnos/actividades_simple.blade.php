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

    .header-card {
        background: linear-gradient(135deg, #1e847d 0%, #2a9d8f 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .header-card h3 {
        margin: 0 0 1rem 0;
        font-size: 1.8rem;
    }

    .header-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-details p {
        margin: 0.25rem 0;
        opacity: 0.9;
    }

    .header-actions {
        text-align: right;
    }

    .badge-count {
        background: rgba(255,255,255,0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        margin-bottom: 1rem;
        display: inline-block;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-outline-light {
        background: transparent;
        color: white;
        border: 1px solid rgba(255,255,255,0.5);
    }

    .btn-outline-light:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        text-decoration: none;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-card.bg-primary { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
    .stat-card.bg-success { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    .stat-card.bg-info { background: linear-gradient(135deg, #17a2b8, #117a8b); color: white; }
    .stat-card.bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800); color: white; }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .filters-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .filters-content {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: center;
    }

    .search-group {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.5rem;
    }

    .search-group i {
        color: #1e847d;
        margin-right: 0.5rem;
    }

    .search-group input {
        border: none;
        background: none;
        flex: 1;
        padding: 0.25rem;
        font-size: 1rem;
    }

    .search-group input:focus {
        outline: none;
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 2px solid #1e847d;
        background: white;
        color: #1e847d;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }

    .filter-btn.active,
    .filter-btn:checked + label {
        background: #1e847d;
        color: white;
    }

    .filter-btn input {
        display: none;
    }

    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .activity-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .activity-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .activity-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .activity-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .activity-badge.bg-success { background: #28a745; color: white; }
    .activity-badge.bg-warning { background: #ffc107; color: #333; }
    .activity-badge.bg-primary { background: #007bff; color: white; }

    .activity-content {
        padding: 1.5rem;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .activity-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
        flex: 1;
    }

    .activity-type {
        background: #6c757d;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        margin-left: 0.5rem;
    }

    .activity-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .activity-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    .stat-item {
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 6px;
    }

    .stat-value {
        font-weight: bold;
        color: #1e847d;
        display: block;
        font-size: 1.1rem;
    }

    .stat-name {
        font-size: 0.75rem;
        color: #666;
        text-transform: uppercase;
        margin-top: 0.25rem;
    }

    .activity-progress {
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s;
    }

    .progress-bar.bg-success { background: #28a745; }
    .progress-bar.bg-warning { background: #ffc107; }

    .activity-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-primary {
        background: #1e847d;
        color: white;
        flex: 1;
    }

    .btn-outline-primary {
        background: transparent;
        color: #1e847d;
        border: 1px solid #1e847d;
        flex: 1;
    }

    .btn-outline-info {
        background: transparent;
        color: #17a2b8;
        border: 1px solid #17a2b8;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .history-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }

    .history-toggle {
        background: none;
        border: none;
        color: #1e847d;
        text-decoration: underline;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .history-list {
        margin-top: 0.5rem;
        background: #f8f9fa;
        border-radius: 6px;
        padding: 0.75rem;
        max-height: 150px;
        overflow-y: auto;
    }

    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
        border-left: 3px solid transparent;
        padding-left: 0.5rem;
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
    }

    .history-item:last-child {
        margin-bottom: 0;
    }

    .history-item.success {
        border-left-color: #28a745;
    }

    .history-item.warning {
        border-left-color: #ffc107;
    }

    .history-date {
        color: #666;
        font-size: 0.8rem;
    }

    .no-results {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .no-results i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    .deadline-footer {
        background: #f8f9fa;
        padding: 0.75rem 1.5rem;
        margin: -1.5rem -1.5rem 0 -1.5rem;
        border-top: 1px solid #dee2e6;
        font-size: 0.85rem;
        color: #666;
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

        .header-info {
            flex-direction: column;
            text-align: center;
        }

        .header-actions {
            text-align: center;
        }

        .filters-content {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .filter-buttons {
            justify-content: center;
            flex-wrap: wrap;
        }

        .activities-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
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
            <li>{{ $parcial->cuatrimestre->nombre }}</li>
            <li class="active">{{ $parcial->nombre }}</li>
        </ol>
    </nav>

    <!-- Header del parcial -->
    <div class="header-card">
        <div class="header-info">
            <div class="header-details">
                <h3>
                    <i class="fas fa-tasks"></i> {{ $parcial->nombre }}
                </h3>
                <p>{{ $parcial->cuatrimestre->nombre }} - {{ $alumnoData['carrera'] }}</p>
                <p>
                    <i class="fas fa-user"></i> {{ $alumnoData['nombre'] }} 
                    | <i class="fas fa-id-card"></i> {{ $alumnoData['matricula'] }}
                </p>
            </div>
            <div class="header-actions">
                <div class="badge-count">
                    {{ $actividades->count() }} Actividades Disponibles
                </div>
                <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left"></i> Volver a Mis Grupos
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas del parcial -->
    <div class="stats-grid">
        <div class="stat-card bg-primary">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-number">{{ $actividades->count() }}</div>
            <div class="stat-label">Total Actividades</div>
        </div>
        
        <div class="stat-card bg-success">
            @php
                $completadas = $intentos->keys()->count();
            @endphp
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $completadas }}</div>
            <div class="stat-label">Completadas</div>
        </div>
        
        <div class="stat-card bg-info">
            @php
                $pendientes = $actividades->count() - $completadas;
            @endphp
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $pendientes }}</div>
            <div class="stat-label">Pendientes</div>
        </div>
        
        <div class="stat-card bg-warning">
            @php
                $promedio = $intentos->flatten()->avg('porcentaje') ?? 0;
            @endphp
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number">{{ number_format($promedio, 1) }}%</div>
            <div class="stat-label">Promedio</div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="filters-card">
        <div class="filters-content">
            <div class="search-group">
                <i class="fas fa-search"></i>
                <input type="text" id="buscarActividad" placeholder="Buscar actividades...">
            </div>
            <div class="filter-buttons">
                <label class="filter-btn active">
                    <input type="radio" name="filtroEstado" value="todas" checked>
                    Todas
                </label>
                <label class="filter-btn">
                    <input type="radio" name="filtroEstado" value="completadas">
                    Completadas
                </label>
                <label class="filter-btn">
                    <input type="radio" name="filtroEstado" value="pendientes">
                    Pendientes
                </label>
            </div>
        </div>
    </div>

    <!-- Lista de actividades -->
    <div class="activities-grid" id="actividadesContainer">
        @forelse($actividades as $actividad)
            @php
                $intentosActividad = $intentos->get($actividad->id, collect());
                $mejorIntento = $intentosActividad->sortByDesc('porcentaje')->first();
                $totalIntentos = $intentosActividad->count();
                $intentosRestantes = $actividad->intentos_permitidos - $totalIntentos;
                $completada = $totalIntentos > 0;
                $contenido = $actividad->contenido;
                $tipoActividad = $contenido['tipo'] ?? 'quiz';
                
                // Determinar color y estado
                if ($completada) {
                    $estadoColor = $mejorIntento->porcentaje >= 70 ? 'success' : 'warning';
                    $estadoTexto = $mejorIntento->porcentaje >= 70 ? 'Aprobada' : 'Completada';
                    $estadoIcono = $mejorIntento->porcentaje >= 70 ? 'check-circle' : 'exclamation-circle';
                } else {
                    $estadoColor = 'primary';
                    $estadoTexto = 'Pendiente';
                    $estadoIcono = 'play-circle';
                }
            @endphp
            
            <div class="activity-card" 
                 data-nombre="{{ strtolower($actividad->nombre) }}"
                 data-estado="{{ $completada ? 'completada' : 'pendiente' }}">
                
                <!-- Imagen de la actividad -->
                @if($actividad->imagen)
                    <div class="activity-image">
                        <img src="{{ asset('storage/' . $actividad->imagen) }}" 
                             alt="{{ $actividad->nombre }}">
                        <div class="activity-badge bg-{{ $estadoColor }}">
                            <i class="fas fa-{{ $estadoIcono }}"></i> {{ $estadoTexto }}
                        </div>
                    </div>
                @endif

                <div class="activity-content">
                    <!-- Título y tipo -->
                    <div class="activity-header">
                        <h6 class="activity-title">{{ $actividad->nombre }}</h6>
                        <span class="activity-type">
                            @switch($tipoActividad)
                                @case('quiz')
                                    <i class="fas fa-question-circle"></i> Quiz
                                    @break
                                @case('completar')
                                    <i class="fas fa-edit"></i> Completar
                                    @break
                                @case('listening')
                                    <i class="fas fa-headphones"></i> Listening
                                    @break
                                @default
                                    <i class="fas fa-file"></i> Actividad
                            @endswitch
                        </span>
                    </div>

                    <!-- Descripción -->
                    @if($actividad->descripcion)
                        <p class="activity-description">
                            {{ Str::limit($actividad->descripcion, 100) }}
                        </p>
                    @endif

                    <!-- Estadísticas -->
                    <div class="activity-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ $totalIntentos }}</span>
                            <div class="stat-name">Intentos</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $intentosRestantes }}</span>
                            <div class="stat-name">Restantes</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $mejorIntento ? number_format($mejorIntento->porcentaje, 0) . '%' : '-' }}</span>
                            <div class="stat-name">Mejor</div>
                        </div>
                    </div>

                    <!-- Barra de progreso -->
                    @if($completada)
                        <div class="activity-progress">
                            <div class="progress-bar bg-{{ $mejorIntento->porcentaje >= 70 ? 'success' : 'warning' }}" 
                                 style="width: {{ $mejorIntento->porcentaje }}%"></div>
                        </div>
                    @endif

                    <!-- Botones de acción -->
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

                    <!-- Historial de intentos -->
                    @if($totalIntentos > 0)
                        <div class="history-section">
                            <button class="history-toggle" onclick="toggleHistorial({{ $actividad->id }})">
                                <i class="fas fa-history"></i> Ver historial ({{ $totalIntentos }})
                            </button>
                            
                            <div id="historial-{{ $actividad->id }}" class="history-list" style="display: none;">
                                @foreach($intentosActividad->sortByDesc('created_at') as $intento)
                                    <div class="history-item {{ $intento->porcentaje >= 70 ? 'success' : 'warning' }}">
                                        <div>
                                            <strong>Intento {{ $intento->numero_intento }}</strong>
                                            <span style="background: {{ $intento->porcentaje >= 70 ? '#28a745' : '#ffc107' }}; color: {{ $intento->porcentaje >= 70 ? 'white' : '#333' }}; padding: 0.1rem 0.4rem; border-radius: 3px; font-size: 0.75rem; margin-left: 0.5rem;">
                                                {{ number_format($intento->porcentaje, 1) }}%
                                            </span>
                                        </div>
                                        <div class="history-date">
                                            {{ $intento->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Fechas importantes -->
                @if($actividad->fecha_limite)
                    <div class="deadline-footer">
                        <i class="fas fa-clock"></i> 
                        Límite: {{ \Carbon\Carbon::parse($actividad->fecha_limite)->format('d/m/Y H:i') }}
                    </div>
                @endif
            </div>
        @empty
            <div style="grid-column: 1 / -1;">
                <div class="no-results">
                    <i class="fas fa-folder-open"></i>
                    <h4>No hay actividades disponibles</h4>
                    <p>Actualmente no tienes actividades asignadas para este parcial.</p>
                    <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Volver a Mis Grupos
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Mensaje de no resultados -->
    <div id="noResultados" class="no-results" style="display: none;">
        <i class="fas fa-search"></i>
        <h5>No se encontraron actividades</h5>
        <p>Intenta cambiar los filtros o el término de búsqueda.</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscarInput = document.getElementById('buscarActividad');
    const filtrosEstado = document.querySelectorAll('input[name="filtroEstado"]');
    const filterLabels = document.querySelectorAll('.filter-btn');
    const actividadesContainer = document.getElementById('actividadesContainer');
    const noResultados = document.getElementById('noResultados');

    // Manejar filtros visuales
    filtrosEstado.forEach(filtro => {
        filtro.addEventListener('change', function() {
            filterLabels.forEach(label => label.classList.remove('active'));
            this.parentElement.classList.add('active');
            filtrarActividades();
        });
    });

    // Función para filtrar actividades
    function filtrarActividades() {
        const textoBusqueda = buscarInput.value.toLowerCase();
        const estadoSeleccionado = document.querySelector('input[name="filtroEstado"]:checked').value;
        const actividades = document.querySelectorAll('.activity-card');
        let actividadesVisibles = 0;

        actividades.forEach(actividad => {
            const nombre = actividad.getAttribute('data-nombre');
            const estado = actividad.getAttribute('data-estado');
            
            let mostrar = true;

            // Filtro por texto
            if (textoBusqueda && !nombre.includes(textoBusqueda)) {
                mostrar = false;
            }

            // Filtro por estado
            if (estadoSeleccionado !== 'todas') {
                if (estadoSeleccionado === 'completadas' && estado !== 'completada') {
                    mostrar = false;
                }
                if (estadoSeleccionado === 'pendientes' && estado !== 'pendiente') {
                    mostrar = false;
                }
            }

            if (mostrar) {
                actividad.style.display = 'block';
                actividadesVisibles++;
            } else {
                actividad.style.display = 'none';
            }
        });

        // Mostrar mensaje de no resultados
        if (actividadesVisibles === 0) {
            noResultados.style.display = 'block';
            actividadesContainer.style.display = 'none';
        } else {
            noResultados.style.display = 'none';
            actividadesContainer.style.display = 'grid';
        }
    }

    // Event listeners
    buscarInput.addEventListener('input', filtrarActividades);
});

// Función para toggle del historial
function toggleHistorial(actividadId) {
    const historial = document.getElementById(`historial-${actividadId}`);
    historial.style.display = historial.style.display === 'none' ? 'block' : 'none';
}

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