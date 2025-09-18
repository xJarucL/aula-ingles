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
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-title {
        text-align: center;
        margin: 2rem 0;
    }

    .page-title h1 {
        color: #1e847d;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .page-title p {
        color: #666;
        font-size: 1.1rem;
    }

    .nav-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-primary {
        background: #1e847d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
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
        color: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-card.stat-green {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .stat-card.stat-teal {
        background: linear-gradient(135deg, #1e847d, #16a2a0);
    }

    .stat-card.stat-orange {
        background: linear-gradient(135deg, #ffc107, #ff9800);
    }

    .stat-card.stat-blue {
        background: linear-gradient(135deg, #17a2b8, #007bff);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        opacity: 0.9;
        font-size: 1rem;
    }

    .history-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .history-card h2 {
        color: #1e847d;
        margin-bottom: 1.5rem;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .attempt-item {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }

    .attempt-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: #1e847d;
    }

    .attempt-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .attempt-info {
        flex: 1;
        min-width: 250px;
    }

    .attempt-info h4 {
        color: #1e847d;
        margin: 0 0 0.5rem 0;
        font-size: 1.2rem;
    }

    .attempt-details {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .attempt-details p {
        margin: 0.25rem 0;
    }

    .attempt-score {
        text-align: center;
        min-width: 150px;
    }

    .score-badge {
        color: white;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .score-badge.excellent {
        background: #28a745;
    }

    .score-badge.good {
        background: #ffc107;
        color: #333;
    }

    .score-badge.needs-work {
        background: #dc3545;
    }

    .score-percentage {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }

    .score-fraction {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .score-status {
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .attempt-actions {
        margin-top: 0.75rem;
    }

    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        border-radius: 4px;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    .empty-state h3 {
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .progress-chart {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .progress-chart h3 {
        color: #1e847d;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-container {
        display: flex;
        justify-content: space-between;
        align-items: end;
        height: 150px;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 10px;
        gap: 0.75rem;
    }

    .chart-bar {
        flex: 1;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: end;
    }

    .bar {
        border-radius: 4px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: end;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
        font-weight: bold;
        padding: 0.25rem 0;
        min-height: 20px;
        transition: all 0.3s;
    }

    .bar:hover {
        transform: scale(1.05);
    }

    .bar.excellent {
        background: #28a745;
    }

    .bar.good {
        background: #ffc107;
        color: #333;
    }

    .bar.needs-work {
        background: #dc3545;
    }

    .chart-label {
        font-size: 0.7rem;
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

        .page-title h1 {
            font-size: 2rem;
        }

        .nav-buttons {
            flex-direction: column;
            align-items: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .attempt-header {
            flex-direction: column;
            text-align: center;
        }

        .attempt-info {
            text-align: center;
        }

        .chart-container {
            height: 120px;
            padding: 1rem;
        }

        .history-card {
            padding: 1.5rem;
        }
    }
</style>



<div class="container">
    <!-- Título -->
    <div class="page-title">
        <h1>📊 Mi Historial de Actividades</h1>
        <p>Revisa tu progreso y calificaciones, {{ $alumnoData['nombre'] }}</p>
    </div>

    <!-- Navegación -->
    <div class="nav-buttons">
        <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-secondary">
            ← Volver a Mis Grupos
        </a>
        <a href="{{ route('alumnos.panel') }}" class="btn btn-primary">
            🏠 Inicio
        </a>
    </div>

    <!-- Estadísticas generales - DATOS REALES -->
    <div class="stats-grid">
        <div class="stat-card stat-green">
            <div class="stat-number">{{ $estadisticas['actividades_completadas'] }}</div>
            <div class="stat-label">Actividades Completadas</div>
        </div>
        
        <div class="stat-card stat-teal">
            <div class="stat-number">{{ number_format($estadisticas['promedio_general'], 1) }}%</div>
            <div class="stat-label">Promedio General</div>
        </div>
        
        <div class="stat-card stat-orange">
            @php
                $mejorCalificacion = 0;
                if ($intentos->count() > 0) {
                    $mejorCalificacion = $intentos->max('porcentaje') ?? 0;
                }
            @endphp
            <div class="stat-number">{{ number_format($mejorCalificacion, 1) }}%</div>
            <div class="stat-label">Mejor Calificación</div>
        </div>
        
        <div class="stat-card stat-blue">
            @php
                $tiempoTotal = $intentos->sum('tiempo_completado');
                $tiempoHoras = floor($tiempoTotal / 3600);
                $tiempoMinutos = floor(($tiempoTotal % 3600) / 60);
            @endphp
            <div class="stat-number">{{ $tiempoHoras }}h {{ $tiempoMinutos }}m</div>
            <div class="stat-label">Tiempo de Estudio</div>
        </div>
    </div>

    <!-- Lista de intentos - DATOS REALES -->
    <div class="history-card">
        <h2>📋 Historial Detallado</h2>
        
        @if($intentos->count() > 0)
            @foreach($intentos as $intento)
                @php
                    $porcentaje = ($intento->total_preguntas > 0) ? round(($intento->puntaje / $intento->total_preguntas) * 100, 1) : 0;
                    $claseBadge = $porcentaje >= 70 ? 'excellent' : ($porcentaje >= 50 ? 'good' : 'needs-work');
                    $textoEstado = $porcentaje >= 70 ? 'Excelente' : ($porcentaje >= 50 ? 'Satisfactorio' : 'Necesita Mejorar');
                @endphp
                
                <div class="attempt-item">
                    <div class="attempt-header">
                        <div class="attempt-info">
                            <h4>{{ $intento->actividad->nombre }}</h4>
                            <div class="attempt-details">
                                <p><strong>Materia:</strong> {{ $intento->actividad->parcial->nombre ?? 'N/A' }}</p>
                                <p><strong>Cuatrimestre:</strong> {{ $intento->actividad->parcial->cuatrimestre->nombre ?? 'N/A' }}</p>
                                <p><strong>Realizada:</strong> {{ $intento->created_at->format('d/m/Y H:i') }}</p>
                                @if($intento->tiempo_completado > 0)
                                    <p><strong>Tiempo:</strong> {{ gmdate('i:s', $intento->tiempo_completado) }} minutos</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="attempt-score">
                            <div class="score-badge {{ $claseBadge }}">
                                <div class="score-percentage">{{ $porcentaje }}%</div>
                                <div class="score-fraction">{{ $intento->puntaje }}/{{ $intento->total_preguntas }}</div>
                            </div>
                            <div class="score-status" style="color: {{ $porcentaje >= 70 ? '#28a745' : ($porcentaje >= 50 ? '#ffc107' : '#dc3545') }};">
                                {{ $textoEstado }}
                            </div>
                            <div class="attempt-actions">
                                <a href="{{ route('alumnos.resultado', $intento->id) }}" class="btn btn-info btn-small">
                                    👁️ Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>Aún no has realizado actividades</h3>
                <p>
                    Explora los parciales disponibles y comienza a realizar las actividades de inglés.
                </p>
                <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-primary">
                    🚀 Ver Actividades Disponibles
                </a>
            </div>
        @endif
    </div>

    @if($intentos->count() > 0)
        <!-- Gráfico de progreso -->
        <div class="progress-chart">
            <h3>📈 Progreso en el Tiempo</h3>
            
            <div class="chart-container">
                @php
                    $ultimosCinco = $intentos->take(5);
                    $maxPorcentaje = $ultimosCinco->max(function($intento) {
                        return $intento->total_preguntas > 0 ? ($intento->puntaje / $intento->total_preguntas) * 100 : 0;
                    });
                    $maxPorcentaje = max($maxPorcentaje, 1); // Evitar división por cero
                @endphp
                
                @foreach($ultimosCinco->reverse() as $intento)
                    @php
                        $porcentaje = ($intento->total_preguntas > 0) ? round(($intento->puntaje / $intento->total_preguntas) * 100, 1) : 0;
                        $altura = ($porcentaje / $maxPorcentaje) * 100;
                        $claseColor = $porcentaje >= 70 ? 'excellent' : ($porcentaje >= 50 ? 'good' : 'needs-work');
                    @endphp
                    
                    <div class="chart-bar">
                        <div class="bar {{ $claseColor }}" style="height: {{ $altura }}%;">
                            {{ $porcentaje }}%
                        </div>
                        <div class="chart-label">
                            {{ $intento->created_at->format('d/m') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection