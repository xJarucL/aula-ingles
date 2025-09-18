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

    .student-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .student-header h3 {
        color: #1e847d;
        margin-bottom: 1rem;
        font-size: 1.8rem;
    }

    .student-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .student-info p {
        margin: 0;
        font-weight: 500;
        color: #333;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
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
        font-size: 0.9rem;
    }

    .btn-light {
        background: #f8f9fa;
        color: #1e847d;
        border: 1px solid #dee2e6;
    }

    .btn-outline-light {
        background: transparent;
        color: #1e847d;
        border: 1px solid #1e847d;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .progress-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .progress-header {
        background: #f8f9fa;
        padding: 1rem;
        margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        border-radius: 15px 15px 0 0;
        border-bottom: 1px solid #dee2e6;
    }

    .progress-header h5 {
        margin: 0;
        color: #1e847d;
        font-size: 1.2rem;
    }

    .progress-stats {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 2rem;
        align-items: center;
    }

    .circle-progress {
        position: relative;
        width: 80px;
        height: 80px;
    }

    .circle-progress canvas {
        width: 80px;
        height: 80px;
    }

    .percentage {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1rem;
        font-weight: bold;
        color: #1e847d;
    }

    .stat-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .stat-card {
        background: #1e847d;
        color: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
    }

    .stat-card.bg-primary { background: linear-gradient(135deg, #007bff, #0056b3); }
    .stat-card.bg-info { background: linear-gradient(135deg, #17a2b8, #117a8b); }
    .stat-card.bg-success { background: linear-gradient(135deg, #28a745, #1e7e34); }

    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .parciales-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .parciales-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .parcial-card {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
    }

    .parcial-card.parcial-actual {
        border-color: #007bff;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
    }

    .parcial-card.parcial-completado {
        border-color: #28a745;
        background: rgba(40, 167, 69, 0.05);
    }

    .parcial-card.parcial-bloqueado {
        opacity: 0.6;
        background: rgba(108, 117, 125, 0.05);
    }

    .parcial-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge.bg-success { background: #28a745; color: white; }
    .badge.bg-primary { background: #007bff; color: white; }
    .badge.bg-secondary { background: #6c757d; color: white; }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-outline-primary {
        background: transparent;
        color: #007bff;
        border: 1px solid #007bff;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .quick-actions {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
    }

    .quick-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
    }

    .quick-action:hover {
        border-color: #1e847d;
        background: #f8fffe;
        color: #1e847d;
        text-decoration: none;
    }

    .quick-action i {
        font-size: 1.5rem;
    }

    .quick-action small {
        font-size: 0.8rem;
        text-align: center;
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

        .student-info {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .header-actions {
            justify-content: center;
        }

        .progress-stats {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .parciales-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    /* Toast notification styles */
    .toast-notice {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 2000;
        min-width: 260px;
        max-width: 420px;
        padding: 12px 16px;
        border-radius: 10px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        transform: translateY(-10px) scale(0.98);
        opacity: 0;
        transition: all 260ms ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .toast-notice.visible { transform: translateY(0) scale(1); opacity: 1; }
    .toast-notice.toast-success { background: linear-gradient(90deg,#16a085,#1e847d); }
    .toast-notice.toast-error { background: linear-gradient(90deg,#e74c3c,#c0392b); }
    .toast-notice .close-btn { margin-left: auto; background: rgba(255,255,255,0.15); border: none; color: white; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-weight: 700; }
</style>



<div class="container">
    <!-- Header con información del alumno -->
    <div class="student-header">
        <h3>¡Hola {{ $alumnoData['nombre'] }}! 👋</h3>
        <div class="student-info">
            <p><i class="fas fa-id-card"></i> <strong>Matrícula:</strong> {{ $alumnoData['matricula'] }}</p>
            <p><i class="fas fa-graduation-cap"></i> <strong>Carrera:</strong> {{ $alumnoData['carrera'] }}</p>
            <p><i class="fas fa-calendar"></i> <strong>Cuatrimestre:</strong> {{ $alumnoData['cuatrimestre'] }}°</p>
            <p><i class="fas fa-clock"></i> <strong>Parcial Actual:</strong> {{ $alumnoData['parcial'] }}°</p>
                @if(isset($alumnoData['seleccion']) && $alumnoData['seleccion']['grupo_codigo'])
                    <p><i class="fas fa-users"></i> <strong>Grupo asignado:</strong> {{ $alumnoData['seleccion']['grupo_codigo'] }}</p>
                @endif
        </div>
        <div class="header-actions">
            <a href="{{ route('alumnos.historial') }}" class="btn btn-light">
                <i class="fas fa-chart-line"></i> Mi Historial
            </a>
            <a href="{{ route('alumnos.cerrar-sesion') }}" class="btn btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- Progreso del parcial actual -->
    @if(isset($progresoParcial) && $progresoParcial)
        <div class="progress-card">
            <div class="progress-header">
                <h5>
                    <i class="fas fa-tasks"></i> Progreso del {{ $parcialActual->nombre ?? 'Parcial Actual' }}
                </h5>
            </div>
            <div class="progress-stats">
                <div>
                    <div class="circle-progress" data-percentage="{{ $progresoParcial->porcentaje_completo }}">
                        <canvas width="80" height="80"></canvas>
                        <div class="percentage">{{ $progresoParcial->porcentaje_completo }}%</div>
                    </div>
                    <small style="display: block; text-align: center; margin-top: 0.5rem; color: #666;">Completado</small>
                </div>
                <div class="stat-cards">
                    <div class="stat-card bg-primary">
                        <div class="stat-number">{{ $progresoParcial->actividades_completadas }}</div>
                        <div class="stat-label">Actividades Completadas</div>
                    </div>
                    <div class="stat-card bg-info">
                        <div class="stat-number">{{ $progresoParcial->total_actividades }}</div>
                        <div class="stat-label">Total de Actividades</div>
                    </div>
                    <div class="stat-card bg-success">
                        <div class="stat-number">{{ number_format($progresoParcial->promedio_calificaciones ?? 0, 1) }}</div>
                        <div class="stat-label">Promedio</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Navegación por parciales -->
    @if(isset($cuatrimestreActual) && $parciales->count() > 0)
        <div class="parciales-card">
            <div class="progress-header">
                <h5>
                    <i class="fas fa-folder-open"></i> {{ $cuatrimestreActual->nombre }} - Parciales Disponibles
                </h5>
            </div>
            <div class="parciales-grid">
                @foreach($parciales as $parcial)
                    @php
                        $puedeAcceder = $parcial->numero <= $alumnoData['parcial'];
                        $esActual = $parcial->numero == $alumnoData['parcial'];
                        $completado = $parcial->numero < $alumnoData['parcial'];
                    @endphp
                    
                    <div class="parcial-card {{ $esActual ? 'parcial-actual' : ($completado ? 'parcial-completado' : 'parcial-bloqueado') }}">
                        <div class="parcial-icon">
                            @if($completado)
                                <i class="fas fa-check-circle" style="color: #28a745;"></i>
                            @elseif($esActual)
                                <i class="fas fa-play-circle" style="color: #007bff;"></i>
                            @else
                                <i class="fas fa-lock" style="color: #6c757d;"></i>
                            @endif
                        </div>
                        <h6>{{ $parcial->nombre }}</h6>
                        <p style="margin: 0.5rem 0;">
                            @if($completado)
                                <span class="badge bg-success">Completado</span>
                            @elseif($esActual)
                                <span class="badge bg-primary">Actual</span>
                            @else
                                <span class="badge bg-secondary">Bloqueado</span>
                            @endif
                        </p>
                        
                        @if($puedeAcceder)
                            <a href="{{ route('alumnos.actividades', $parcial->id) }}" 
                               class="btn {{ $esActual ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-eye"></i> Ver Actividades
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-lock"></i> No Disponible
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Mensaje de error si no hay cuatrimestre activo -->
    @if(isset($error))
        <div style="background: #fff3cd; color: #856404; padding: 1.5rem; border-radius: 8px; border: 1px solid #ffeaa7; margin-bottom: 2rem;">
            <h4 style="margin: 0 0 0.5rem 0;">Atención</h4>
            <p style="margin: 0 0 1rem 0;">{{ $error }}</p>
            <p style="margin: 0; font-size: 0.9rem;">Por favor, contacta a tu coordinador académico para más información.</p>
        </div>
    @endif

    <!-- Accesos rápidos -->
    <div class="quick-actions">
        <div class="progress-header">
            <h5>
                <i class="fas fa-rocket"></i> Accesos Rápidos
            </h5>
        </div>
        <div class="quick-actions-grid">
            <a href="{{ route('alumnos.historial') }}" class="quick-action">
                <i class="fas fa-history"></i>
                <small>Mi Historial</small>
            </a>
            <a href="{{ route('alumnos.panel') }}" class="quick-action">
                <i class="fas fa-edit"></i>
                <small>Cambiar Carrera</small>
            </a>
            <button class="quick-action" onclick="mostrarEstadisticas()" style="background: none; border: 1px solid #dee2e6;">
                <i class="fas fa-chart-bar"></i>
                <small>Mis Estadísticas</small>
            </button>
            <a href="{{ route('alumnos.cerrar-sesion') }}" class="quick-action">
                <i class="fas fa-sign-out-alt"></i>
                <small>Cerrar Sesión</small>
            </a>
        </div>
    </div>
</div>

<!-- Modal para estadísticas -->
<div id="estadisticasModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 15px; padding: 2rem; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h5 style="margin: 0; color: #1e847d;">Mis Estadísticas</h5>
            <button onclick="cerrarEstadisticas()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <div id="estadisticasContent">
            <!-- Se cargará via AJAX -->
        </div>
    </div>
</div>

<script>
// Función para mostrar estadísticas
function mostrarEstadisticas() {
    // Por ahora mostraremos estadísticas simuladas
    // TODO: Implementar endpoint de estadísticas en el controlador
    const estadisticas = {
        total_intentos: 0,
        promedio_general: 0,
        actividades_completadas: 0,
        mejor_calificacion: 0
    };

    const content = `
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            <div class="stat-card bg-primary">
                <div class="stat-number">${estadisticas.total_intentos}</div>
                <div class="stat-label">Total Intentos</div>
            </div>
            <div class="stat-card bg-success">
                <div class="stat-number">${estadisticas.promedio_general}%</div>
                <div class="stat-label">Promedio General</div>
            </div>
            <div class="stat-card bg-info">
                <div class="stat-number">${estadisticas.actividades_completadas}</div>
                <div class="stat-label">Actividades Completadas</div>
            </div>
            <div style="background: linear-gradient(135deg, #ffc107, #ff9800); color: white; padding: 1rem; border-radius: 8px; text-align: center;">
                <div class="stat-number">${estadisticas.mejor_calificacion}%</div>
                <div class="stat-label">Mejor Calificación</div>
            </div>
        </div>
    `;
    document.getElementById('estadisticasContent').innerHTML = content;
    document.getElementById('estadisticasModal').style.display = 'flex';
}

function cerrarEstadisticas() {
    document.getElementById('estadisticasModal').style.display = 'none';
}

// Crear progreso circular
document.addEventListener('DOMContentLoaded', function() {
    const progressElements = document.querySelectorAll('.circle-progress');
    
    progressElements.forEach(element => {
        const canvas = element.querySelector('canvas');
        const ctx = canvas.getContext('2d');
        const percentage = parseInt(element.getAttribute('data-percentage'));
        
        const centerX = 40;
        const centerY = 40;
        const radius = 30;
        
        // Fondo del círculo
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 6;
        ctx.stroke();
        
        // Progreso
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, -Math.PI/2, (-Math.PI/2) + (2 * Math.PI * percentage / 100));
        ctx.strokeStyle = '#1e847d';
        ctx.lineWidth = 6;
        ctx.lineCap = 'round';
        ctx.stroke();
    });
});

// Mostrar notificaciones tipo toast para éxito/error
document.addEventListener('DOMContentLoaded', function() {
    const existingToast = document.getElementById('app-toast-notice');
    if (existingToast) existingToast.remove();

    @if(session('success'))
        const toast = document.createElement('div');
        toast.id = 'app-toast-notice';
        toast.className = 'toast-notice toast-success';
        toast.innerHTML = `<span>\u2705</span><div style="flex:1">{{ addslashes(session('success')) }}</div><button class="close-btn">OK</button>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('visible'), 50);
        toast.querySelector('.close-btn').addEventListener('click', () => toast.remove());
        setTimeout(() => { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 6000);
    @endif

    @if(session('error'))
        const toastErr = document.createElement('div');
        toastErr.id = 'app-toast-notice';
        toastErr.className = 'toast-notice toast-error';
        toastErr.innerHTML = `<span>\u26A0</span><div style="flex:1">{{ addslashes(session('error')) }}</div><button class="close-btn">OK</button>`;
        document.body.appendChild(toastErr);
        setTimeout(() => toastErr.classList.add('visible'), 50);
        toastErr.querySelector('.close-btn').addEventListener('click', () => toastErr.remove());
        setTimeout(() => { if (toastErr.parentNode) toastErr.parentNode.removeChild(toastErr); }, 7000);
    @endif
});
</script>
@endsection