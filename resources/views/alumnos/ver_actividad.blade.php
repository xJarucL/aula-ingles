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
        max-width: 900px;
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

    .activity-info {
        background: linear-gradient(135deg, #1e847d 0%, #2a9d8f 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: flex-start;
    }

    .info-main h2 {
        margin: 0 0 1rem 0;
        font-size: 1.8rem;
    }

    .info-main p {
        margin: 0.5rem 0;
        opacity: 0.9;
        line-height: 1.5;
    }

    .info-details {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .timer-card {
        background: rgba(255,255,255,0.2);
        padding: 1rem 1.5rem;
        border-radius: 20px;
        text-align: center;
        backdrop-filter: blur(10px);
        min-width: 150px;
    }

    .timer-display {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }

    .timer-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .previous-attempts {
        background: rgba(255,255,255,0.2);
        padding: 1rem;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        min-width: 250px;
        margin-top: 1rem;
    }

    .previous-attempts h4 {
        margin: 0 0 0.75rem 0;
        font-size: 1rem;
    }

    .attempt-item {
        margin: 0.5rem 0;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .activity-image {
        text-align: center;
        margin-bottom: 2rem;
    }

    .activity-image img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .exam-container {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }

    .exam-title {
        color: #1e847d;
        font-size: 2rem;
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .exam-subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 3rem;
        font-size: 1.1rem;
        line-height: 1.5;
    }

    .question-card {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s;
    }

    .question-card:hover {
        border-color: #1e847d;
        background: #f8fffe;
    }

    .question-header {
        color: #1e847d;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .options {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .option {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .option:hover {
        border-color: #1e847d;
        background: #f0f8ff;
        transform: translateX(5px);
    }

    .option.selected {
        border-color: #1e847d;
        background: #e3f2fd;
    }

    .option input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: #1e847d;
    }

    .option-text {
        flex: 1;
        font-weight: 500;
    }

    .option-letter {
        font-weight: bold;
        color: #1e847d;
        margin-right: 0.5rem;
    }

    .completar-input {
        background: #fff;
        border: 2px solid #1e847d;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        min-width: 120px;
        display: inline-block;
        margin: 0 0.25rem;
        transition: all 0.3s;
    }

    .completar-input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 132, 125, 0.1);
        border-color: #16a085;
    }

    .exercise-card {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .exercise-header {
        color: #1e847d;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .exercise-content {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 1.1rem;
        line-height: 2;
    }

    .submit-section {
        text-align: center;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid #e9ecef;
    }

    .submit-btn {
        background: linear-gradient(135deg, #1e847d 0%, #16a085 100%);
        color: white;
        padding: 1rem 3rem;
        border: none;
        border-radius: 25px;
        font-size: 1.3rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(30, 132, 125, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 132, 125, 0.4);
    }

    .debug-info {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        color: #666;
    }

    .debug-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .debug-details {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: left;
        margin: 1rem 0;
    }

    .debug-details summary {
        cursor: pointer;
        font-weight: bold;
        color: #1e847d;
        margin-bottom: 0.5rem;
    }

    .debug-details pre {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 5px;
        overflow: auto;
        max-height: 300px;
        font-size: 0.8rem;
        border: 1px solid #dee2e6;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        max-width: 700px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .modal-header {
        padding: 2rem 2rem 1rem;
        text-align: center;
        border-bottom: 1px solid #e9ecef;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        padding: 1rem 2rem 2rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .result-summary {
        text-align: center;
        margin-bottom: 2rem;
    }

    .score-display {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .score-main {
        font-size: 3rem;
        font-weight: bold;
    }

    .score-details {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .score-message {
        font-size: 1.1rem;
        font-weight: 600;
        color: #666;
        margin-top: 1rem;
    }

    .score-passed {
        color: #28a745;
    }

    .score-failed {
        color: #dc3545;
    }

    .result-details {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .result-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .result-item.correct {
        background: #d4edda;
        border-color: #c3e6cb;
    }

    .result-item.incorrect {
        background: #f8d7da;
        border-color: #f5c6cb;
    }

    .result-icon {
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }

    .result-content p {
        margin: 0 0 0.5rem 0;
        font-size: 0.9rem;
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

        .info-grid {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .info-details {
            justify-content: center;
            flex-wrap: wrap;
        }

        .exam-container {
            padding: 2rem 1.5rem;
        }

        .option {
            padding: 0.75rem 1rem;
        }

        .submit-btn {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
        }

        .modal-content {
            width: 95%;
        }

        .modal-footer {
            flex-direction: column;
        }
    }
</style>



<div class="container">
    <!-- Título de la página -->
    <div class="page-title">
        <h1>📝 {{ $actividad->nombre }}</h1>
        <p>¡Hola {{ $alumnoData['nombre'] }}! Realiza esta actividad</p>
    </div>

    <!-- Navegación -->
    <div class="nav-buttons">
        <a href="{{ route('alumnos.actividades', $actividad->parcial_id) }}" class="btn btn-secondary">
            ← Volver a Actividades
        </a>
        <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-primary">
            🏠 Mis Grupos
        </a>
    </div>
        
    <!-- Información de la actividad -->
    <div class="activity-info">
        <div class="info-grid">
            <div class="info-main">
                <h2>{{ $actividad->nombre }}</h2>
                
                @if($actividad->descripcion)
                    <p>{{ $actividad->descripcion }}</p>
                @endif
                
                <div class="info-details">
                    <span><strong>Parcial:</strong> {{ $actividad->parcial->nombre }}</span>
                    <span><strong>Cuatrimestre:</strong> {{ $actividad->parcial->cuatrimestre->nombre }}</span>
                </div>
            </div>

            <div>
                <div class="timer-card">
                    <div class="timer-display" id="timer-display">00:00</div>
                    <div class="timer-label">⏱️ Tiempo transcurrido</div>
                </div>

                <!-- Intentos previos -->
                @if($intentosPrevios->count() > 0)
                    <div class="previous-attempts">
                        <h4>📊 Tus Intentos Anteriores</h4>
                        @foreach($intentosPrevios->take(3) as $intento)
                            @php
                                $porcentaje = $intento->total_preguntas > 0 ? round(($intento->puntaje / $intento->total_preguntas) * 100, 1) : 0;
                            @endphp
                            <div class="attempt-item">
                                <strong>Intento {{ $loop->iteration }}:</strong> {{ $intento->puntaje }}/{{ $intento->total_preguntas }} ({{ $porcentaje }}%)
                                <br><span style="opacity: 0.7; font-size: 0.8rem;">{{ $intento->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                        
                        @if($intentosPrevios->count() > 3)
                            <div style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.8;">
                                <a href="{{ route('alumnos.historial') }}" style="color: rgba(255,255,255,0.8); text-decoration: underline;">
                                    Ver todos ({{ $intentosPrevios->count() }} intentos)
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Imagen de la actividad -->
    @if($actividad->imagen)
        <div class="activity-image">
            <img src="{{ Storage::url($actividad->imagen) }}" alt="{{ $actividad->nombre }}">
        </div>
    @endif

    <!-- Contenido de la actividad -->
    @php
        $contenido = $actividad->contenido;
        $tipo = is_array($contenido) ? ($contenido['tipo'] ?? 'manual') : 'manual';
        
        $preguntas = [];
        $ejercicios = [];
        
        if (is_array($contenido)) {
            // Para QUIZ - buscar en todas las ubicaciones
            if ($tipo === 'quiz') {
                if (isset($contenido['contenido']['preguntas']) && is_array($contenido['contenido']['preguntas'])) {
                    $preguntas = $contenido['contenido']['preguntas'];
                } elseif (isset($contenido['preguntas']) && is_array($contenido['preguntas'])) {
                    $preguntas = $contenido['preguntas'];
                }
            }
            
            if ($tipo === 'completar') {
                if (isset($contenido['contenido']['ejercicios']) && is_array($contenido['contenido']['ejercicios'])) {
                    $ejercicios = $contenido['contenido']['ejercicios'];
                } elseif (isset($contenido['ejercicios']) && is_array($contenido['ejercicios'])) {
                    $ejercicios = $contenido['ejercicios'];
                }
            }
        }
    @endphp

    @if($tipo === 'quiz' && count($preguntas) > 0)
        <!-- Quiz de opción múltiple -->
        <div class="exam-container">
            <h2 class="exam-title">Examen de prueba</h2>
            <p class="exam-subtitle">
                Demuestra tus conocimientos. Lee cada pregunta cuidadosamente y selecciona la respuesta correcta. ¡Éxito!
            </p>
            
            <form id="quiz-form" onsubmit="completarActividad(event)">
                @foreach($preguntas as $index => $pregunta)
                    <div class="question-card">
                        <h4 class="question-header">
                            {{ $pregunta['pregunta'] ?? 'Pregunta sin texto' }}
                        </h4>
                        
                        @if(isset($pregunta['opciones']) && is_array($pregunta['opciones']))
                            <div class="options">
                                @foreach($pregunta['opciones'] as $opcionKey => $opcionTexto)
                                    <label class="option" onclick="seleccionarRespuesta(this)">
                                        <input type="radio" name="respuesta_{{ $index }}" value="{{ $opcionKey }}" onchange="seleccionarRespuesta(this.parentElement)">
                                        <span class="option-text">
                                            <span class="option-letter">{{ $opcionKey }})</span> {{ $opcionTexto }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p style="color: #dc3545; font-weight: bold;">⚠️ Esta pregunta no tiene opciones configuradas</p>
                        @endif
                    </div>
                @endforeach
                
                <div class="submit-section">
                    <button type="submit" class="submit-btn" id="btn-enviar">
                        Siguiente
                    </button>
                </div>
            </form>
        </div>
    @elseif($tipo === 'completar' && count($ejercicios) > 0)
        <!-- Ejercicio de completar -->
        <div class="exam-container">
            <h2 class="exam-title">Completa las siguientes oraciones</h2>
            <p class="exam-subtitle">
                Completa los espacios en blanco con la palabra o frase correcta. ({{ count($ejercicios) }} ejercicios)
            </p>
            
            <form id="completar-form" onsubmit="completarActividad(event)">
                @foreach($ejercicios as $index => $ejercicio)
                    <div class="exercise-card">
                        <h4 class="exercise-header">
                            Ejercicio {{ $index + 1 }}
                        </h4>
                        
                        @php
                            $oracion = $ejercicio['oracion'] ?? $ejercicio['texto'] ?? 'Oración no disponible';
                            $oracionConInput = preg_replace('/_{1,}/', '<input type="text" name="completar_' . $index . '" class="completar-input" placeholder="..." required>', $oracion);
                        @endphp
                        
                        <div class="exercise-content">
                            {!! $oracionConInput !!}
                        </div>
                    </div>
                @endforeach
                
                <div class="submit-section">
                    <button type="submit" class="submit-btn" id="btn-enviar">
                        Siguiente
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="exam-container">
            <div class="debug-info">
                <div class="debug-icon">🔧</div>
                <h3 style="color: #dc3545; margin-bottom: 1rem;">🐛 Debug de Contenido</h3>
                
                <div class="debug-details">
                    <p><strong>Tipo detectado:</strong> {{ $tipo }}</p>
                    <p><strong>Preguntas encontradas:</strong> {{ count($preguntas) }}</p>
                    <p><strong>Ejercicios encontrados:</strong> {{ count($ejercicios) }}</p>
                    <p><strong>Estructura del contenido:</strong> {{ is_array($contenido) ? implode(', ', array_keys($contenido)) : 'No es array' }}</p>
                    
                    @if(is_array($contenido) && isset($contenido['contenido']))
                        <p><strong>Estructura contenido.contenido:</strong> {{ is_array($contenido['contenido']) ? implode(', ', array_keys($contenido['contenido'])) : 'No es array' }}</p>
                    @endif
                    
                    <details style="margin-top: 1rem;">
                        <summary>Ver contenido completo</summary>
                        <pre>{{ json_encode($contenido, JSON_PRETTY_PRINT) }}</pre>
                    </details>
                </div>
                
                @if(isset($contenido['contenido']['descripcion']) && $contenido['contenido']['descripcion'])
                    <p style="font-size: 1.1rem; line-height: 1.6; max-width: 600px; margin: 0 auto;">
                        {{ $contenido['contenido']['descripcion'] }}
                    </p>
                @else
                    <p style="color: #999; font-style: italic;">
                        Esta actividad no tiene contenido interactivo disponible o está mal configurada.
                    </p>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Modal de resultados -->
<div id="resultadoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>🎉 ¡Actividad Completada!</h2>
        </div>
        <div class="modal-body" id="resultadoContent">
            <!-- El contenido se llenará con JavaScript -->
        </div>
        <div class="modal-footer">
            <button onclick="cerrarModal()" class="btn btn-secondary">Cerrar</button>
            <button onclick="reintentar()" class="btn btn-primary">Intentar de nuevo</button>
            <a href="{{ route('alumnos.actividades', $actividad->parcial_id) }}" class="btn btn-primary">Ver más actividades</a>
        </div>
    </div>
</div>

<script>
// Variables globales
let tiempoInicio = Date.now();
let timerInterval;
let actividadCompletada = false;

// Inicializar timer
document.addEventListener('DOMContentLoaded', function() {
    iniciarTimer();
});

function iniciarTimer() {
    timerInterval = setInterval(function() {
        const tiempoTranscurrido = Math.floor((Date.now() - tiempoInicio) / 1000);
        const minutos = Math.floor(tiempoTranscurrido / 60);
        const segundos = tiempoTranscurrido % 60;
        
        document.getElementById('timer-display').textContent = 
            `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    }, 1000);
}

function detenerTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
    }
}

function completarActividad(event) {
    event.preventDefault(); 
    
    if (actividadCompletada) return;
    
    actividadCompletada = true;
    detenerTimer();
    
    const tiempoTotal = Math.floor((Date.now() - tiempoInicio) / 1000);
    const respuestas = recopilarRespuestas();
    
    console.log('🚀 Enviando respuestas:', respuestas);
    
    fetch(`{{ route('alumnos.procesar-respuesta', $actividad->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            respuestas: respuestas,
            tiempo: tiempoTotal
        })
    })
    .then(response => {
        console.log('📥 Respuesta del servidor:', response);
        return response.json();
    })
    .then(data => {
        console.log('📊 Datos recibidos:', data);
            if (data.success) {
                // Crear objeto resultado compatible con la función mostrarResultado
                const resultado = {
                    puntaje: data.puntaje,
                    total: data.total,
                    porcentaje: data.porcentaje,
                    detalles: data.detalles || [],
                    tipo: 'quiz'
                };
                mostrarResultado(resultado);
        } else {
            alert('❌ Error al procesar las respuestas: ' + (data.message || 'Error desconocido'));
            actividadCompletada = false; // Permitir reintentar
        }
    })
    .catch(error => {
        console.error('💥 Error:', error);
        alert('💥 Error de conexión. Inténtalo de nuevo.');
        actividadCompletada = false; // Permitir reintentar
    });
}

function recopilarRespuestas() {
    const respuestas = {};
    
    // Para quiz
    document.querySelectorAll('input[name^="respuesta_"]').forEach(input => {
        if (input.checked) {
            const preguntaIndex = input.name.replace('respuesta_', '');
            respuestas[preguntaIndex] = input.value;
        }
    });
    
    // Para completar
    document.querySelectorAll('input[name^="completar_"]').forEach(input => {
        const ejercicioIndex = input.name.replace('completar_', '');
        respuestas[ejercicioIndex] = input.value.trim();
    });
    
    console.log('📝 Respuestas recopiladas:', respuestas);
    return respuestas;
}

function mostrarResultado(resultado) {
    const content = document.getElementById('resultadoContent');
    const { puntaje, total, porcentaje, detalles, tipo } = resultado;
    
    let html = `
        <div class="result-summary">
            <div class="score-display">
                <div class="score-main ${porcentaje >= 70 ? 'score-passed' : 'score-failed'}">
                    ${puntaje}/${total}
                </div>
                <div class="score-details ${porcentaje >= 70 ? 'score-passed' : 'score-failed'}">
                    ${porcentaje}%
                </div>
            </div>
            <div class="score-message">
                ${porcentaje >= 90 ? '🌟 ¡Excelente trabajo!' : 
                  porcentaje >= 80 ? '👏 ¡Muy bien!' : 
                  porcentaje >= 70 ? '👍 ¡Bien hecho!' : 
                  porcentaje >= 60 ? '📚 Puedes mejorar' : 
                  '💪 Sigue practicando'}
            </div>
        </div>
    `;
    
    if (detalles && detalles.length > 0) {
        html += '<div class="result-details">';
        detalles.forEach((detalle, index) => {
            const icono = detalle.correcta ? '✅' : '❌';

            // Construir HTML de opciones si existen
            let opcionesHtml = '';
            if (detalle.opciones && Object.keys(detalle.opciones).length > 0) {
                opcionesHtml += '<div><strong>Opciones disponibles:</strong><ul class="options-list">';
                const rcTextNorm = (detalle.respuesta_correcta_text || '').toString().trim().toLowerCase();
                const raTextNorm = (detalle.respuesta_alumno_text || '').toString().trim().toLowerCase();
                for (const [k, v] of Object.entries(detalle.opciones)) {
                    const vText = (v || '').toString();
                    const vNorm = vText.trim().toLowerCase();

                    // Determinar si esta opción es la correcta: preferir key si existe, si no comparar por texto
                    let esCorrecta = false;
                    if (detalle.respuesta_correcta_key) {
                        esCorrecta = String(k).toUpperCase() === String(detalle.respuesta_correcta_key).toUpperCase();
                    }
                    if (!esCorrecta && rcTextNorm) {
                        esCorrecta = (vNorm !== '' && vNorm === rcTextNorm);
                    }

                    // Determinar si esta opción fue la seleccionada por el alumno
                    let esAlumno = false;
                    if (detalle.respuesta_alumno_key) {
                        esAlumno = String(k).toUpperCase() === String(detalle.respuesta_alumno_key).toUpperCase();
                    }
                    if (!esAlumno && raTextNorm) {
                        esAlumno = (vNorm !== '' && vNorm === raTextNorm);
                    }

                    const clase = esCorrecta ? 'option-correct' : (esAlumno && !detalle.correcta ? 'option-incorrect' : '');
                    opcionesHtml += `<li class="${clase}">${k}) ${vText}`;
                    if (esCorrecta) opcionesHtml += `<span style="color: #28a745; margin-left: 10px;">✓ Respuesta correcta</span>`;
                    else if (esAlumno && !detalle.correcta) opcionesHtml += `<span style="color: #dc3545; margin-left: 10px;">✗ Tu respuesta</span>`;
                    opcionesHtml += `</li>`;
                }
                opcionesHtml += '</ul></div>';
            }

            const tuRespuesta = detalle.respuesta_alumno_text || 'Sin respuesta';
            const respuestaCorrectaTxt = detalle.respuesta_correcta_text || 'Sin respuesta';

            html += `
                <div class="result-item ${detalle.correcta ? 'correct' : 'incorrect'}">
                    <span class="result-icon">${icono}</span>
                    <div class="result-content">
                        ${tipo === 'quiz' ? 
                            `<p><strong>Pregunta ${index + 1}:</strong> ${detalle.pregunta || ''}</p>
                             ${opcionesHtml}
                             <p><strong>Tu respuesta:</strong> ${tuRespuesta}</p>
                             <p><strong>Respuesta correcta:</strong> ${respuestaCorrectaTxt}</p>` :
                            `<p><strong>Ejercicio ${index + 1}:</strong> ${detalle.oracion || ''}</p>
                             <p><strong>Tu respuesta:</strong> "${tuRespuesta}"</p>
                             <p><strong>Respuesta correcta:</strong> "${respuestaCorrectaTxt}"</p>`
                        }
                    </div>
                </div>
            `;
        });
        html += '</div>';
    }
    
    content.innerHTML = html;
    document.getElementById('resultadoModal').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('resultadoModal').style.display = 'none';
    // Redirigir a la página de resultados
    window.location.href = '{{ route("alumnos.actividades", $actividad->parcial_id) }}';
}

function reintentar() {
    location.reload();
}

function seleccionarRespuesta(label) {
    // Limpiar selecciones previas del mismo grupo
    const input = label.querySelector('input');
    const groupName = input.name;
    
    document.querySelectorAll(`input[name="${groupName}"]`).forEach(inp => {
        inp.parentElement.classList.remove('selected');
    });
    
    // Marcar como seleccionado
    if (input.checked) {
        label.classList.add('selected');
    }
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('resultadoModal');
    if (event.target === modal) {
        cerrarModal();
    }
}
</script>
@endsection