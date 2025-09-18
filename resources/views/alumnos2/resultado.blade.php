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

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        color: white;
        text-decoration: none;
    }

    .result-header {
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .result-header h2 {
        margin: 0 0 0.5rem 0;
        font-size: 1.8rem;
    }

    .result-header p {
        margin: 0.25rem 0;
        opacity: 0.9;
    }

    .score-card {
        background: rgba(255,255,255,0.2);
        padding: 2rem;
        border-radius: 15px;
        margin: 1.5rem auto;
        max-width: 400px;
        backdrop-filter: blur(10px);
        text-align: center;
    }

    .score-main {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .score-details {
        font-size: 1.2rem;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .score-status {
        font-size: 1rem;
        opacity: 0.9;
    }

    .result-info {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        flex-wrap: wrap;
    }

    .details-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .details-card h3 {
        color: #1e847d;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .question-result {
        border: 2px solid;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .question-result.correct {
        border-color: #28a745;
        background: #d4edda;
    }

    .question-result.incorrect {
        border-color: #dc3545;
        background: #f8d7da;
    }

    .question-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .question-number {
        background: #1e847d;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .question-number.correct {
        background: #28a745;
    }

    .question-number.incorrect {
        background: #dc3545;
    }

    .question-status {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .question-status.correct {
        color: #155724;
    }

    .question-status.incorrect {
        color: #721c24;
    }

    .question-title {
        color: #1e847d;
        margin-bottom: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .options-list {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }

    .options-list li {
        margin: 0.5rem 0;
        font-weight: 500;
    }

    .option-correct {
        color: #155724;
        font-weight: bold;
    }

    .option-incorrect {
        color: #721c24;
        font-weight: bold;
    }

    .answer-summary {
        background: rgba(255,255,255,0.7);
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .answer-summary div {
        margin-bottom: 0.5rem;
    }

    .answer-summary div:last-child {
        margin-bottom: 0;
    }

    .actions-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .actions-card h3 {
        color: #1e847d;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .motivation-message {
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .motivation-excellent {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }

    .motivation-good {
        background: linear-gradient(135deg, #fff3cd, #fde8a3);
        color: #856404;
    }

    .motivation-needs-work {
        background: linear-gradient(135deg, #f8d7da, #f1c6c9);
        color: #721c24;
    }

    .no-details {
        text-align: center;
        padding: 3rem;
        background: #f8f9fa;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
        color: #6c757d;
    }

    .no-details i {
        font-size: 2rem;
        margin-bottom: 1rem;
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

        .result-info {
            flex-direction: column;
            gap: 0.5rem;
        }

        .score-card {
            margin: 1rem 0;
        }

        .score-main {
            font-size: 2.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .question-header {
            flex-wrap: wrap;
        }

        .details-card {
            padding: 1.5rem;
        }
    }
</style>



<div class="container">
    <!-- Título -->
    <div class="page-title">
        <h1>📊 Resultado de la Actividad</h1>
        <p>Revisa tu desempeño, {{ $alumnoData['nombre'] }}</p>
    </div>

    <!-- Navegación -->
    <div class="nav-buttons">
        <a href="{{ route('alumnos.actividades', $intento->actividad->parcial_id) }}" class="btn btn-secondary">
            ← Volver a Actividades
        </a>
        <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-primary">
            🏠 Mis Grupos
        </a>
        <a href="{{ route('alumnos.historial') }}" class="btn btn-info">
            📋 Historial
        </a>
    </div>

    @php
        // CORRECCIÓN CRÍTICA: Cálculo seguro del porcentaje para evitar división por cero
        $porcentaje = 0;
        if ($intento->total_preguntas > 0) {
            $porcentaje = round(($intento->puntaje / $intento->total_preguntas) * 100, 1);
        }
        
        $colorResultado = $porcentaje >= 70 ? '#28a745' : ($porcentaje >= 50 ? '#ffc107' : '#dc3545');
        $estadoTexto = $porcentaje >= 70 ? 'Excelente' : ($porcentaje >= 50 ? 'Satisfactorio' : 'Necesita Mejorar');
    @endphp

    <!-- Resumen del resultado -->
    <div class="result-header" style="background: linear-gradient(135deg, {{ $colorResultado }}, {{ $colorResultado }}dd);">
        <h2>{{ $intento->actividad->nombre }}</h2>
        <p>
            {{ $intento->actividad->parcial->nombre ?? 'Parcial' }} - 
            {{ $intento->actividad->parcial->cuatrimestre->nombre ?? 'Cuatrimestre ' . ($alumnoData['cuatrimestre'] ?? '') }}°
        </p>
        
        <div class="score-card">
            <div class="score-main">{{ $porcentaje }}%</div>
            <div class="score-details">{{ $intento->puntaje }} de {{ $intento->total_preguntas }} respuestas correctas</div>
            <div class="score-status">{{ $estadoTexto }}</div>
        </div>
        
        <div class="result-info">
            <div><strong>Realizada:</strong> {{ $intento->created_at->format('d/m/Y H:i') }}</div>
            @if($intento->tiempo_completado > 0)
                <div><strong>Tiempo:</strong> {{ gmdate('i:s', $intento->tiempo_completado) }} min</div>
            @endif
        </div>
    </div>

    <!-- Detalles de las respuestas -->
    <div class="details-card">
        <h3>📝 Detalles de tus Respuestas</h3>
        
        @php
            $contenido = $intento->actividad->contenido;
            $respuestasAlumno = $intento->respuestas;
            // Asegurar que $respuestasAlumno sea un array (puede venir como JSON string desde la BD)
            if (is_string($respuestasAlumno)) {
                $decoded = json_decode($respuestasAlumno, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $respuestasAlumno = $decoded;
                } else {
                    // Fallback: intentar unserialize o envolver en array
                    $respuestasAlumno = @unserialize($respuestasAlumno) ?: (array) $respuestasAlumno;
                }
            }
            
            // Determinar el tipo de actividad
            $tipo = 'quiz'; // Por defecto
            if (is_array($contenido) && isset($contenido['tipo'])) {
                $tipo = $contenido['tipo'];
            }
            
            // CORRECCIÓN: Obtener las preguntas según el formato de la BD
            $preguntas = [];
            if (is_array($contenido)) {
                if (isset($contenido['contenido']['preguntas'])) {
                    $preguntas = $contenido['contenido']['preguntas'];
                } elseif (isset($contenido['preguntas'])) {
                    $preguntas = $contenido['preguntas'];
                }
            }
        @endphp

        @if(!empty($preguntas))
            @foreach($preguntas as $index => $pregunta)
                @php
                    $respuestaAlumno = $respuestasAlumno[$index] ?? null;
                    $respuestaCorrecta = $pregunta['respuesta_correcta'] ?? $pregunta['correcta'] ?? null;

                    // Normalizar: intentar mapear la respuesta del alumno a una clave de opción (A,B,...) si fue enviada como clave
                    $opcionAlumnoKey = null;
                    $opcionAlumnoText = null;

                    if ($respuestaAlumno !== null) {
                        // Normalizar clave de respuesta del alumno (mayúsculas y trim)
                        if (is_string($respuestaAlumno)) {
                            $respuestaAlumnoNorm = strtoupper(trim((string)$respuestaAlumno));
                        } else {
                            $respuestaAlumnoNorm = strtoupper(trim((string)$respuestaAlumno));
                        }

                        // Si la respuesta coincide con una clave de opciones (A, B, ...)
                        if (isset($pregunta['opciones'][$respuestaAlumnoNorm])) {
                            $opcionAlumnoKey = $respuestaAlumnoNorm;
                            $opcionAlumnoText = $pregunta['opciones'][$opcionAlumnoKey];
                        } else {
                            // Buscar por texto (comparación case-insensitive)
                            foreach (($pregunta['opciones'] ?? []) as $k => $v) {
                                if (mb_strtolower(trim($v)) === mb_strtolower(trim((string)$respuestaAlumno))) {
                                    $opcionAlumnoKey = $k;
                                    $opcionAlumnoText = $v;
                                    break;
                                }
                            }
                        }

                        // Si aún no encontramos texto asociado, usar el valor crudo como texto
                        if ($opcionAlumnoText === null) {
                            $opcionAlumnoText = (string) $respuestaAlumno;
                        }
                    }

                    // Determinar clave/texto de la respuesta correcta
                    $opcionCorrectaKey = null;
                    $opcionCorrectaText = null;
                    if ($respuestaCorrecta !== null) {
                        // Normalizar clave de respuesta correcta
                        $respuestaCorrectaNorm = is_string($respuestaCorrecta) ? strtoupper(trim((string)$respuestaCorrecta)) : strtoupper(trim((string)$respuestaCorrecta));

                        if (isset($pregunta['opciones'][$respuestaCorrectaNorm])) {
                            $opcionCorrectaKey = $respuestaCorrectaNorm;
                            $opcionCorrectaText = $pregunta['opciones'][$opcionCorrectaKey];
                        } else {
                            // Buscar por texto (comparación case-insensitive)
                            foreach (($pregunta['opciones'] ?? []) as $k => $v) {
                                if (mb_strtolower(trim($v)) === mb_strtolower(trim((string)$respuestaCorrecta))) {
                                    $opcionCorrectaKey = $k;
                                    $opcionCorrectaText = $v;
                                    break;
                                }
                            }
                        }

                        if ($opcionCorrectaText === null) {
                            $opcionCorrectaText = (string) $respuestaCorrecta;
                        }
                    }

                    // Determinar si la respuesta del alumno es correcta
                    $esCorrecta = false;
                    if ($respuestaAlumno !== null && $respuestaCorrecta !== null) {
                        if ($opcionAlumnoKey !== null && $opcionCorrectaKey !== null) {
                            $esCorrecta = (strtoupper($opcionAlumnoKey) === strtoupper($opcionCorrectaKey));
                        } else {
                            // Comparar por texto en último recurso
                            $esCorrecta = mb_strtolower(trim((string)$opcionAlumnoText)) === mb_strtolower(trim((string)$opcionCorrectaText));
                        }
                    }
                @endphp
                
                <div class="question-result {{ $esCorrecta ? 'correct' : 'incorrect' }}">
                    <div class="question-header">
                        <div class="question-number {{ $esCorrecta ? 'correct' : 'incorrect' }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="question-status {{ $esCorrecta ? 'correct' : 'incorrect' }}">
                            {{ $esCorrecta ? '✅ Correcto' : '❌ Incorrecto' }}
                        </div>
                    </div>
                    
                    <h4 class="question-title">{{ $pregunta['pregunta'] ?? 'Pregunta ' . ($index + 1) }}</h4>
                    
                    @if(isset($pregunta['opciones']) && is_array($pregunta['opciones']))
                        <div>
                            <strong>Opciones disponibles:</strong>
                            <ul class="options-list">
                                @foreach($pregunta['opciones'] as $opcionKey => $opcionTexto)
                                        @php
                                        // Comparaciones normalizadas para evitar discrepancias
                                        $esEstaLaRespuestaCorrecta = (strtoupper($opcionKey) === strtoupper((string)($opcionCorrectaKey ?? $respuestaCorrecta ?? '')));
                                        $esEstaLaRespuestaDelAlumno = (strtoupper($opcionKey) === strtoupper((string)($opcionAlumnoKey ?? $respuestaAlumno ?? '')));
                                    @endphp
                                    <li class="{{ $esEstaLaRespuestaCorrecta ? 'option-correct' : ($esEstaLaRespuestaDelAlumno && !$esCorrecta ? 'option-incorrect' : '') }}">
                                        {{ $opcionKey }}) {{ $opcionTexto }}
                                        @if($esEstaLaRespuestaCorrecta)
                                            <span style="color: #28a745; margin-left: 10px;">✓ Respuesta correcta</span>
                                        @elseif($esEstaLaRespuestaDelAlumno && !$esCorrecta)
                                            <span style="color: #dc3545; margin-left: 10px;">✗ Tu respuesta</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="answer-summary">
                        <div>
                            <strong>Tu respuesta:</strong>
                            @if($respuestaAlumno !== null)
                                <span style="color: {{ $esCorrecta ? '#155724' : '#721c24' }}; font-weight: bold;">
                                    @if($opcionAlumnoKey)
                                        {{ $opcionAlumnoKey }}) {{ $opcionAlumnoText }}
                                    @else
                                        {{ $opcionAlumnoText }}
                                    @endif
                                </span>
                            @else
                                <span style="color: #721c24; font-weight: bold;">Sin respuesta</span>
                            @endif
                        </div>
                        
                        @if(!$esCorrecta)
                            <div>
                                <strong>Respuesta correcta:</strong>
                                <span style="color: #155724; font-weight: bold;">
                                    @if($opcionCorrectaKey)
                                        {{ $opcionCorrectaKey }}) {{ $opcionCorrectaText }}
                                    @else
                                        {{ $opcionCorrectaText }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <!-- Mostrar mensaje si no hay preguntas detectadas -->
            <div class="no-details">
                <i class="fas fa-question-circle"></i>
                <h4>No se deben mostrar las respuestas</h4>
                <p>
                    Los detalles de las preguntas no se pueden para mostrar, pero tu calificación se registró correctamente:
                    <strong>{{ $intento->puntaje }}/{{ $intento->total_preguntas }} ({{ $porcentaje }}%)</strong>
                </p>
            </div>
        @endif
    </div>

    <!-- Acciones disponibles -->
    <div class="actions-card">
        <h3>🎯 ¿Qué quieres hacer ahora?</h3>
        
        <div class="action-buttons">
            <a href="{{ route('alumnos.ver-actividad', $intento->actividad->id) }}" class="btn btn-success">
                🔄 Repetir Actividad
            </a>
            
            <a href="{{ route('alumnos.actividades', $intento->actividad->parcial_id) }}" class="btn btn-primary">
                🎯 Más Actividades
            </a>
            
            <a href="{{ route('alumnos.historial') }}" class="btn btn-info">
                📊 Ver Historial
            </a>
        </div>
        
        <!-- Mensaje de felicitación o ánimo -->
        <div class="motivation-message {{ $porcentaje >= 70 ? 'motivation-excellent' : ($porcentaje >= 50 ? 'motivation-good' : 'motivation-needs-work') }}">
            @if($porcentaje >= 70)
                🎉 ¡Excelente trabajo! Has demostrado un buen dominio del tema.
            @elseif($porcentaje >= 50)
                👍 ¡Buen esfuerzo! Con un poco más de práctica mejorarás aún más.
            @else
                💪 ¡No te desanimes! Te recomendamos repasar el material y intentar de nuevo.
            @endif
        </div>
    </div>
</div>

<script>
// Prevenir navegación accidental
window.addEventListener('beforeunload', function (e) {
    // Solo mostrar advertencia si están revisando el resultado por primera vez
    if (document.referrer.includes('procesar-respuesta')) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endsection