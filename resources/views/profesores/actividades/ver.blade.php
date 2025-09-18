@extends('layouts.panel')

@section('title', 'Ver Actividad')

@section('content')

    <!-- Contenido Principal -->
    <div class="ver-actividad-container">
        <!-- Header con botones de acción -->
        <div class="ver-actividad-header">
            <div class="header-navegacion">
                <a href="{{ route('profesores.actividades') }}" class="btn-volver">
                    ← Volver a actividades
                </a>

                @if($modo === 'prueba')
                    <div class="modo-prueba-badge">
                        🧪 Modo prueba
                    </div>
                @endif
            </div>

            <div class="header-acciones d-flex gap-2 justify-content-end align-items-center flex-wrap">
                <a href="/profesores/actividades/{{ $actividad->id }}/editar" class="btn-action">
                    <i class="fas fa-pen"></i> Editar
                </a>
                <button onclick="duplicarActividad({{ $actividad->id }})" class="btn-action">
                    <i class="fas fa-copy"></i> Duplicar
                </button>
                @if($modo !== 'prueba')
                    <a href="/profesores/actividades/{{ $actividad->id }}?modo=prueba" target="_blank" class="btn-action">
                        <i class="fas fa-play"></i> Probar
                    </a>
                @endif
                <button onclick="eliminarActividad({{ $actividad->id }})" class="btn-action btn-action-danger">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>

        </div>

        <!-- Información de la actividad -->
        <div class="actividad-info-card">
            <div class="info-header">
                <div class="info-principal">
                    <h1>{{ $actividad->nombre }}</h1>
                    <div class="info-meta">
                        <span class="cuatrimestre-badge">
                            {{ $actividad->nombre_cuatrimestre }} 
                        </span>
                        <span class="parcial-badge">
                            {{ $actividad->nombre_parcial }} 
                        </span>
                        <span class="estado-badge {{ $actividad->activa ? 'activa' : 'inactiva' }}">
                            {{ $actividad->activa ? '✅ Activa' : '❌ Inactiva' }}
                        </span>
                    </div>
                    @if($actividad->descripcion)
                        <p class="descripcion">{{ $actividad->descripcion }}</p>
                    @endif
                </div>

                @if($actividad->imagen)
                    <div class="info-imagen">
                        <img src="{{ $actividad->imagen_url }}" alt="{{ $actividad->nombre }}">
                    </div>
                @endif
            </div>

            <div class="info-detalles">
                <div class="detalle-item">
                    <span class="detalle-label">📅 Creada:</span>
                    <span class="detalle-valor">{{ $actividad->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detalle-item">
                    <span class="detalle-label">🔄 Actualizada:</span>
                    <span class="detalle-valor">{{ $actividad->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detalle-item">
                    <span class="detalle-label">🎯 Tipo:</span>
                    <span
                        class="detalle-valor">{{ ucfirst($actividad->contenido_formateado['tipo'] ?? 'No definido') }}</span>
                </div>
            </div>
        </div>

        <!-- Contenido de la actividad -->
        <div class="actividad-contenido-card">
            <h2>📋 Contenido de la actividad</h2>

            <div id="contenido-actividad">
                @php
                    $contenido = $actividad->contenido_formateado;
                    $tipo = $contenido['tipo'] ?? 'manual';
                @endphp

                @if($tipo === 'quiz')
                    <div class="contenido-quiz mt-3">
                        <div class="tipo-header mb-3 d-flex align-items-center justify-content-between flex-wrap">
                            <h3 class="mb-0">
                                <span style="font-size:1.2em;">🧠</span> Quiz interactivo
                            </h3>
                            <span class="contador badge rounded-pill bg-teal" style="font-size:1em;">
                                {{ count($contenido['contenido']['preguntas'] ?? []) }} preguntas
                            </span>
                        </div>

                        @if(isset($contenido['contenido']['preguntas']) && count($contenido['contenido']['preguntas']) > 0)
                            <div class="container-fluid px-0">
                                <div class="row g-3">
                                    @foreach($contenido['contenido']['preguntas'] as $index => $pregunta)
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                            <div class="pregunta-card shadow-sm h-100 rounded bg-white border p-3 d-flex flex-column">
                                                <div class="pregunta-header mb-3">
                                                    <span class="badge bg-primary me-2">Pregunta {{ $index + 1 }}</span>
                                                    <div class="pregunta-texto fw-bold mt-2">{{ $pregunta['pregunta'] }}</div>
                                                </div>

                                                <div class="opciones-container flex-grow-1">
                                                    @foreach($pregunta['opciones'] as $letra => $opcion)
                                                        <div class="opcion-item d-flex align-items-center rounded px-3 py-2 mb-2 border 
                                                                            @if($letra === $pregunta['correcta']) bg-success-subtle border-success @else bg-light @endif"
                                                            style="font-size: 0.95em;">
                                                            <span class="fw-semibold me-2 text-primary">{{ $letra }})</span>
                                                            <span class="flex-grow-1">{{ $opcion }}</span>
                                                            @if($letra === $pregunta['correcta'])
                                                                <span class="ms-2 text-success fw-bold fs-5">✓</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Agregar clearfix cada 3 elementos para asegurar filas limpias --}}
                                        @if(($index + 1) % 3 === 0)
                                            <div class="w-100 d-none d-lg-block"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="contenido-vacio text-center py-5">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay preguntas configuradas para este quiz.
                                </div>
                            </div>
                        @endif

                @elseif($tipo === 'flashcards')
                        <div class="contenido-flashcards">
                            <div class="tipo-header">
                                <h3>🃏 Tarjetas de estudio</h3>
                                <span class="contador">{{ count($contenido['contenido']['tarjetas'] ?? []) }} tarjetas</span>
                            </div>

                            @if(isset($contenido['contenido']['tarjetas']) && count($contenido['contenido']['tarjetas']) > 0)
                                <div class="flashcards-lista">
                                    @foreach($contenido['contenido']['tarjetas'] as $index => $tarjeta)
                                        <div class="flashcard-card">
                                            <div class="flashcard-numero">Tarjeta {{ $index + 1 }}</div>
                                            <div class="flashcard-contenido">
                                                <div class="flashcard-lado frente">
                                                    <div class="lado-label">Frente</div>
                                                    <div class="lado-texto">{{ $tarjeta['frente'] }}</div>
                                                </div>
                                                <div class="flashcard-separador">↔️</div>
                                                <div class="flashcard-lado atras">
                                                    <div class="lado-label">Atrás</div>
                                                    <div class="lado-texto">{{ $tarjeta['atras'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="contenido-vacio">
                                    <p>No hay tarjetas configuradas.</p>
                                </div>
                            @endif
                        </div>

                    @elseif($tipo === 'completar')
                        <div class="contenido-completar">
                            <div class="tipo-header">
                                <h3>✏️ Ejercicios de Completar</h3>
                                <span class="contador">{{ count($contenido['contenido']['ejercicios'] ?? []) }}
                                    ejercicios</span>
                            </div>

                            @if(isset($contenido['contenido']['ejercicios']) && count($contenido['contenido']['ejercicios']) > 0)
                                <div class="ejercicios-lista">
                                    @foreach($contenido['contenido']['ejercicios'] as $index => $ejercicio)
                                        <div class="ejercicio-card">
                                            <div class="ejercicio-numero">Ejercicio {{ $index + 1 }}</div>
                                            <div class="ejercicio-oracion">{{ $ejercicio['oracion'] }}</div>
                                            <div class="ejercicio-respuesta">
                                                <span class="respuesta-label">Respuesta:</span>
                                                <span class="respuesta-texto">{{ $ejercicio['respuesta'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="contenido-vacio">
                                    <p>No hay ejercicios configurados.</p>
                                </div>
                            @endif
                        </div>

                    @else
                        <div class="contenido-manual">
                            <div class="tipo-header">
                                <h3>📝 Contenido Manual</h3>
                            </div>
                            <div class="manual-contenido">
                                @if(isset($contenido['contenido']['descripcion']) && $contenido['contenido']['descripcion'])
                                    <p>{{ $contenido['contenido']['descripcion'] }}</p>
                                @else
                                    <p class="sin-contenido">No hay descripción disponible para esta actividad.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($modo === 'prueba')
                <!-- Modo prueba interactivo -->
                <div class="modo-prueba-container mt-4">
                    <div class="text-center mb-4">
                        <h2>🧪 Modo prueba interactivo</h2>
                        <div class="prueba-info">
                            <p class="text-muted">Esta es una vista previa de cómo verán los estudiantes esta actividad.</p>
                        </div>
                    </div>

                    @if($tipo === 'quiz')
                        <div id="quiz-interactivo" class="quiz-interactivo bg-white p-4 rounded shadow">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Cargando quiz interactivo...</p>
                            </div>
                        </div>
                    @elseif($tipo === 'completar')
                        <div id="completar-interactivo" class="completar-interactivo bg-white p-4 rounded shadow">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Cargando ejercicios interactivos...</p>
                            </div>
                        </div>
                    @elseif($tipo === 'flashcards')
                        <div id="flashcards-interactivo" class="flashcards-interactivo bg-white p-4 rounded shadow">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Cargando flashcards interactivas...</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5>📝 Actividad Manual</h5>
                            <p>Esta actividad no tiene contenido interactivo para probar, ya que es de tipo manual.</p>
                            <p><strong>Descripción:</strong> {{ $contenido['contenido']['descripcion'] ?? 'Sin descripción' }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- JavaScript para modo prueba -->
        <script>
        @if($modo === 'prueba')
            document.addEventListener('DOMContentLoaded', function() {
                const contenido = @json($contenido);
                const tipo = '{{ $tipo }}';
                
                console.log('Iniciando modo prueba:', tipo, contenido);
                
                if (tipo === 'quiz') {
                    inicializarQuizInteractivo(contenido);
                } else if (tipo === 'completar') {
                    inicializarCompletarInteractivo(contenido);
                } else if (tipo === 'flashcards') {
                    inicializarFlashcardsInteractivo(contenido);
                }
            });

            // === QUIZ INTERACTIVO ===
            function inicializarQuizInteractivo(contenido) {
                const container = document.getElementById('quiz-interactivo');
                const preguntas = contenido.contenido?.preguntas || [];
                
                if (preguntas.length === 0) {
                    container.innerHTML = '<div class="alert alert-warning">No hay preguntas para mostrar en el modo prueba.</div>';
                    return;
                }
                
                let preguntaActual = 0;
                let respuestasUsuario = {};
                
                function mostrarPregunta(indice) {
                    const pregunta = preguntas[indice];
                    
                    container.innerHTML = `
                        <div class="quiz-prueba">
                            <div class="quiz-progreso mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Pregunta ${indice + 1} de ${preguntas.length}</span>
                                    <div class="progress" style="width: 200px;">
                                        <div class="progress-bar bg-success" style="width: ${((indice + 1) / preguntas.length) * 100}%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pregunta-actual">
                                <h4 class="pregunta-texto mb-4">${pregunta.pregunta}</h4>
                                
                                <div class="opciones-interactivas">
                                    ${Object.entries(pregunta.opciones).map(([letra, opcion]) => `
                                        <button type="button" class="opcion-btn btn btn-outline-primary w-100 mb-2 text-start" 
                                                onclick="seleccionarRespuesta('${letra}', this)">
                                            <span class="fw-bold me-3">${letra})</span> ${opcion}
                                        </button>
                                    `).join('')}
                                </div>
                                
                                <div class="navegacion-quiz mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="anteriorPregunta()" 
                                            ${indice === 0 ? 'disabled' : ''}>
                                        ← Anterior
                                    </button>
                                    
                                    <button type="button" id="btnSiguiente" class="btn btn-primary" onclick="siguientePregunta()" disabled>
                                        ${indice === preguntas.length - 1 ? 'Finalizar Quiz' : 'Siguiente →'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Marcar respuesta previa si existe
                    if (respuestasUsuario[indice]) {
                        const botonRespuesta = container.querySelector(`button[onclick*="${respuestasUsuario[indice]}"]`);
                        if (botonRespuesta) {
                            seleccionarRespuesta(respuestasUsuario[indice], botonRespuesta);
                        }
                    }
                }
                
                window.seleccionarRespuesta = function(letra, boton) {
                    // Limpiar selecciones previas
                    container.querySelectorAll('.opcion-btn').forEach(btn => {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    });
                    
                    // Marcar como seleccionada
                    boton.classList.remove('btn-outline-primary');
                    boton.classList.add('btn-primary');
                    
                    // Guardar respuesta
                    respuestasUsuario[preguntaActual] = letra;
                    
                    // Habilitar botón siguiente
                    document.getElementById('btnSiguiente').disabled = false;
                };
                
                window.siguientePregunta = function() {
                    if (preguntaActual < preguntas.length - 1) {
                        preguntaActual++;
                        mostrarPregunta(preguntaActual);
                    } else {
                        mostrarResultados();
                    }
                };
                
                window.anteriorPregunta = function() {
                    if (preguntaActual > 0) {
                        preguntaActual--;
                        mostrarPregunta(preguntaActual);
                    }
                };
                
                function mostrarResultados() {
                    let correctas = 0;
                    let resultadosHtml = '';
                    
                    preguntas.forEach((pregunta, indice) => {
                        const respuestaUsuario = respuestasUsuario[indice];
                        const esCorrecta = respuestaUsuario === pregunta.correcta;
                        if (esCorrecta) correctas++;
                        
                        resultadosHtml += `
                            <div class="resultado-pregunta mb-3 p-3 border rounded ${esCorrecta ? 'border-success bg-light-success' : 'border-danger bg-light-danger'}">
                                <h6>Pregunta ${indice + 1}: ${pregunta.pregunta}</h6>
                                <p class="mb-1">
                                    <strong>Tu respuesta:</strong> ${respuestaUsuario ? `${respuestaUsuario}) ${pregunta.opciones[respuestaUsuario]}` : 'Sin respuesta'}
                                    ${esCorrecta ? '<span class="text-success ms-2">✓ Correcta</span>' : '<span class="text-danger ms-2">✗ Incorrecta</span>'}
                                </p>
                                ${!esCorrecta ? `<p class="mb-0"><strong>Respuesta correcta:</strong> ${pregunta.correcta}) ${pregunta.opciones[pregunta.correcta]}</p>` : ''}
                            </div>
                        `;
                    });
                    
                    const porcentaje = Math.round((correctas / preguntas.length) * 100);
                    
                    container.innerHTML = `
                        <div class="resultados-quiz">
                            <div class="text-center mb-4">
                                <h3 class="text-primary">🎉 Quiz Completado</h3>
                                <div class="resultado-final p-4 bg-light rounded">
                                    <h4>Resultado: ${correctas}/${preguntas.length} (${porcentaje}%)</h4>
                                    <div class="progress mt-2">
                                        <div class="progress-bar ${porcentaje >= 70 ? 'bg-success' : porcentaje >= 50 ? 'bg-warning' : 'bg-danger'}" 
                                             style="width: ${porcentaje}%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="resultados-detalle">
                                <h5>Detalle de respuestas:</h5>
                                ${resultadosHtml}
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-primary" onclick="reiniciarQuiz()">
                                    🔄 Intentar de nuevo
                                </button>
                                <button type="button" class="btn btn-secondary ms-2" onclick="window.close()">
                                    Cerrar prueba
                                </button>
                            </div>
                        </div>
                    `;
                }
                
                window.reiniciarQuiz = function() {
                    preguntaActual = 0;
                    respuestasUsuario = {};
                    mostrarPregunta(0);
                };
                
                // Inicializar quiz
                mostrarPregunta(0);
            }

            // === COMPLETAR INTERACTIVO ===
            function inicializarCompletarInteractivo(contenido) {
                const container = document.getElementById('completar-interactivo');
                const ejercicios = contenido.contenido?.ejercicios || [];
                
                if (ejercicios.length === 0) {
                    container.innerHTML = '<div class="alert alert-warning">No hay ejercicios para mostrar en el modo prueba.</div>';
                    return;
                }
                
                let respuestasUsuario = {};
                
                function mostrarEjercicios() {
                    let ejerciciosHtml = ejercicios.map((ejercicio, indice) => {
                        // Reemplazar ___ con input
                        const oracionConInput = ejercicio.oracion.replace(/___/g, 
                            `<input type="text" class="form-control d-inline-block mx-2" style="width: 150px;" 
                                   id="respuesta_${indice}" onchange="guardarRespuesta(${indice}, this.value)">`);
                        
                        return `
                            <div class="ejercicio-interactivo mb-4 p-3 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary me-2">Ejercicio ${indice + 1}</span>
                                </div>
                                <div class="ejercicio-texto fs-5">
                                    ${oracionConInput}
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    container.innerHTML = `
                        <div class="completar-prueba">
                            <div class="mb-4">
                                <h4>✏️ Completa las oraciones</h4>
                                <p class="text-muted">Escribe la palabra que falta en cada espacio en blanco.</p>
                            </div>
                            
                            ${ejerciciosHtml}
                            
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-success" onclick="verificarCompletarRespuestas()">
                                    ✓ Verificar Respuestas
                                </button>
                                <button type="button" class="btn btn-secondary ms-2" onclick="limpiarCompletarRespuestas()">
                                    🗑️ Limpiar
                                </button>
                            </div>
                            
                            <div id="resultados-completar" class="mt-4"></div>
                        </div>
                    `;
                }
                
                window.guardarRespuesta = function(indice, valor) {
                    respuestasUsuario[indice] = valor.trim();
                };
                
                window.verificarCompletarRespuestas = function() {
                    let correctas = 0;
                    let resultadosHtml = '';
                    
                    ejercicios.forEach((ejercicio, indice) => {
                        const respuestaUsuario = respuestasUsuario[indice] || '';
                        const respuestaCorrecta = ejercicio.respuesta.toLowerCase();
                        const esCorrecta = respuestaUsuario.toLowerCase() === respuestaCorrecta;
                        
                        if (esCorrecta) correctas++;
                        
                        resultadosHtml += `
                            <div class="resultado-ejercicio mb-2 p-2 border rounded ${esCorrecta ? 'border-success bg-light-success' : 'border-danger bg-light-danger'}">
                                <strong>Ejercicio ${indice + 1}:</strong> 
                                Tu respuesta: "${respuestaUsuario}" 
                                ${esCorrecta ? '<span class="text-success">✓</span>' : `<span class="text-danger">✗ Correcta: "${ejercicio.respuesta}"</span>`}
                            </div>
                        `;
                    });
                    
                    const porcentaje = Math.round((correctas / ejercicios.length) * 100);
                    
                    document.getElementById('resultados-completar').innerHTML = `
                        <div class="resultados-completar">
                            <h5>Resultados: ${correctas}/${ejercicios.length} (${porcentaje}%)</h5>
                            ${resultadosHtml}
                        </div>
                    `;
                };
                
                window.limpiarCompletarRespuestas = function() {
                    respuestasUsuario = {};
                    container.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                    document.getElementById('resultados-completar').innerHTML = '';
                };
                
                mostrarEjercicios();
            }

            // === FLASHCARDS INTERACTIVO ===
            function inicializarFlashcardsInteractivo(contenido) {
                const container = document.getElementById('flashcards-interactivo');
                const tarjetas = contenido.contenido?.tarjetas || [];
                
                if (tarjetas.length === 0) {
                    container.innerHTML = '<div class="alert alert-warning">No hay tarjetas para mostrar en el modo prueba.</div>';
                    return;
                }
                
                let tarjetaActual = 0;
                let mostrandoFrente = true;
                
                function mostrarTarjeta() {
                    const tarjeta = tarjetas[tarjetaActual];
                    
                    container.innerHTML = `
                        <div class="flashcard-prueba text-center">
                            <div class="mb-3">
                                <span class="badge bg-info">Tarjeta ${tarjetaActual + 1} de ${tarjetas.length}</span>
                            </div>
                            
                            <div class="tarjeta-container" onclick="voltearTarjeta()" style="cursor: pointer;">
                                <div class="tarjeta ${mostrandoFrente ? 'frente' : 'atras'}" 
                                     style="min-height: 200px; display: flex; align-items: center; justify-content: center; 
                                            background: ${mostrandoFrente ? '#e3f2fd' : '#e8f5e8'}; 
                                            border: 2px solid ${mostrandoFrente ? '#2196f3' : '#4caf50'}; 
                                            border-radius: 15px; font-size: 1.5rem; font-weight: bold;">
                                    ${mostrandoFrente ? tarjeta.frente : tarjeta.atras}
                                </div>
                                <p class="mt-2 text-muted">
                                    ${mostrandoFrente ? '👆 Haz clic para ver la respuesta' : '👆 Haz clic para volver al frente'}
                                </p>
                            </div>
                            
                            <div class="navegacion-flashcards mt-4">
                                <button type="button" class="btn btn-secondary" onclick="anteriorTarjeta()" 
                                        ${tarjetaActual === 0 ? 'disabled' : ''}>
                                    ← Anterior
                                </button>
                                <button type="button" class="btn btn-primary ms-2" onclick="siguienteTarjeta()" 
                                        ${tarjetaActual === tarjetas.length - 1 ? 'disabled' : ''}>
                                    Siguiente →
                                </button>
                            </div>
                        </div>
                    `;
                }
                
                window.voltearTarjeta = function() {
                    mostrandoFrente = !mostrandoFrente;
                    mostrarTarjeta();
                };
                
                window.siguienteTarjeta = function() {
                    if (tarjetaActual < tarjetas.length - 1) {
                        tarjetaActual++;
                        mostrandoFrente = true;
                        mostrarTarjeta();
                    }
                };
                
                window.anteriorTarjeta = function() {
                    if (tarjetaActual > 0) {
                        tarjetaActual--;
                        mostrandoFrente = true;
                        mostrarTarjeta();
                    }
                };
                
                mostrarTarjeta();
            }
        @endif

        // === FUNCIONES ADICIONALES ===
        function duplicarActividad(id) {
            if (confirm('¿Estás seguro de que quieres duplicar esta actividad?')) {
                fetch(`/profesores/actividades/${id}/duplicar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Actividad duplicada exitosamente');
                        window.location.href = '/profesores/actividades';
                    } else {
                        alert('❌ Error al duplicar la actividad');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Error al duplicar la actividad');
                });
            }
        }

        function eliminarActividad(id) {
            if (confirm('⚠️ ¿Estás seguro de que quieres eliminar esta actividad? Esta acción no se puede deshacer.')) {
                fetch(`/profesores/actividades/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Actividad eliminada exitosamente');
                        window.location.href = '/profesores/actividades';
                    } else {
                        alert('❌ Error al eliminar la actividad');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Error al eliminar la actividad');
                });
            }
        }
        </script>
    </div>

@endsection