@extends('layouts.panel')

@section('title', 'Editar Actividad')

@section('content')

    <!-- Contenido Principal -->
    <div class="editar-actividad-container">
        <div class="editar-actividad-header">
            <div class="header-navegacion">
                <a href="/profesores/actividades/{{ $actividad->id }}" class="btn-volver">
                    ← Volver a Ver Actividad
                </a>
            </div>
            <h1>✏️ Editar: {{ $actividad->nombre }}</h1>
        </div>

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="alert-error">
                <h4>❌ Hay algunos errores:</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mostrar mensaje de éxito -->
        @if (session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Formulario de Edición -->
        <form action="{{ route('profesores.actividades.update', $actividad->id) }}" method="POST" enctype="multipart/form-data" class="editar-form" id="formEditarActividad">
            @csrf
            @method('PUT')

            <div class="form-layout">
                <!-- Columna Izquierda: Información Básica -->
                <div class="columna-izquierda">
                    <div class="seccion-card">
                        <h3>📝 Información básica</h3>
                        
                        <div class="form-group">
                            <label for="nombre">Nombre de la actividad *</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $actividad->nombre) }}" 
                                   placeholder="Ej: Present Simple Practice" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3" 
                                placeholder="Describe brevemente de qué trata esta actividad...">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="cuatrimestre_id">Cuatrimestre *</label>
                                <select id="cuatrimestre_id" name="cuatrimestre_id" required>
                                    <option value="">Selecciona cuatrimestre...</option>
                                    @foreach($cuatrimestres as $cuatri)
                                        <option value="{{ $cuatri->id }}"
                                            {{ old('cuatrimestre_id', $actividad->parcial->cuatrimestre->id ?? null) == $cuatri->id ? 'selected' : '' }}>
                                            {{ $cuatri->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="parcial_id">Parcial *</label>
                                <select id="parcial_id" name="parcial_id" required>
                                    <option value="">Selecciona primero el cuatrimestre...</option>
                                    {{-- Opciones se llenan por JS --}}
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="activa" value="1" {{ old('activa', $actividad->activa) ? 'checked' : '' }}>
                                Actividad activa
                            </label>
                            <small>Las actividades inactivas no serán visibles para los estudiantes</small>
                        </div>

                        <div class="form-group">
                            <label>Imagen de la actividad</label>
                            @if($actividad->imagen)
                                <div class="imagen-actual">
                                    <h4>Imagen actual:</h4>
                                    <img src="{{ $actividad->imagen_url }}" alt="Imagen actual" style="max-width: 200px; border-radius: 8px;">
                                    <div class="imagen-actual-acciones">
                                        <label>
                                            <input type="checkbox" name="eliminar_imagen" value="1">
                                            Eliminar imagen actual
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <div class="imagen-upload-area" onclick="seleccionarImagen()">
                                <div class="imagen-preview" id="imagenPreview">
                                    <div class="placeholder-image">
                                        📸
                                        <p>{{ $actividad->imagen ? 'Cambiar imagen' : 'Seleccionar imagen' }}</p>
                                        <small>PNG, JPG, GIF hasta 2MB</small>
                                    </div>
                                </div>
                            </div>
                            <input type="file" id="imagen" name="imagen" accept="image/*" style="display: none;" onchange="previsualizarImagen(this)">
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Contenido de la Actividad -->
                <div class="columna-derecha">
                    <div class="seccion-card">
                        @php
                            $contenidoActual = $actividad->contenido_formateado;
                            $tipoActual = $contenidoActual['tipo'] ?? 'quiz';
                        @endphp

                        <h3>🎯 Contenido de la actividad</h3>
                        
                        <!-- Mostrar tipo actual (solo informativo) -->
                        <div class="tipo-actual-info">
                            <p><strong>Tipo de actividad:</strong> 
                                @if($tipoActual === 'quiz')
                                    🧠 Quiz
                                @elseif($tipoActual === 'completar') 
                                    ✏️ Completar
                                @elseif($tipoActual === 'flashcards')
                                    🃏 Flashcards
                                @else
                                    📝 Manual
                                @endif
                            </p>
                        </div>

                        <div class="contenido-dinamico">
                            <div class="contenido-area">
                                @if($tipoActual === 'quiz')
                                    <!-- CONTENIDO QUIZ -->
                                    <h4>📋 Preguntas del Quiz</h4>
                                    <div id="preguntas-container">
                                        @if(isset($contenidoActual['contenido']['preguntas']))
                                            @foreach($contenidoActual['contenido']['preguntas'] as $index => $pregunta)
                                                <div class="pregunta-item" id="pregunta_{{ $index }}">
                                                    <div class="pregunta-header">
                                                        <span class="pregunta-numero">{{ $index + 1 }}</span>
                                                        <button type="button" class="btn-eliminar" onclick="eliminarPregunta('pregunta_{{ $index }}')">❌</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Pregunta</label>
                                                        <input type="text" class="pregunta-texto" value="{{ $pregunta['pregunta'] }}" placeholder="Escribe tu pregunta aquí..." required>
                                                    </div>
                                                    <div class="opciones-grid">
                                                        <div class="form-group">
                                                            <label>Opción A</label>
                                                            <input type="text" class="opcion-input" data-opcion="A" value="{{ $pregunta['opciones']['A'] ?? '' }}" placeholder="Opción A" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Opción B</label>
                                                            <input type="text" class="opcion-input" data-opcion="B" value="{{ $pregunta['opciones']['B'] ?? '' }}" placeholder="Opción B" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Opción C</label>
                                                            <input type="text" class="opcion-input" data-opcion="C" value="{{ $pregunta['opciones']['C'] ?? '' }}" placeholder="Opción C" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Opción D</label>
                                                            <input type="text" class="opcion-input" data-opcion="D" value="{{ $pregunta['opciones']['D'] ?? '' }}" placeholder="Opción D" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Respuesta Correcta</label>
                                                        <select class="respuesta-correcta" required>
                                                            <option value="">Seleccionar...</option>
                                                            <option value="A" {{ ($pregunta['correcta'] ?? '') === 'A' ? 'selected' : '' }}>A</option>
                                                            <option value="B" {{ ($pregunta['correcta'] ?? '') === 'B' ? 'selected' : '' }}>B</option>
                                                            <option value="C" {{ ($pregunta['correcta'] ?? '') === 'C' ? 'selected' : '' }}>C</option>
                                                            <option value="D" {{ ($pregunta['correcta'] ?? '') === 'D' ? 'selected' : '' }}>D</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn-agregar" onclick="agregarPreguntaQuiz()">
                                        ➕ Agregar pregunta
                                    </button>

                                @elseif($tipoActual === 'completar')
                                    <!-- CONTENIDO COMPLETAR -->
                                    <h4>✏️ Ejercicios de Completar</h4>
                                    <div id="ejercicios-container">
                                        @if(isset($contenidoActual['contenido']['ejercicios']))
                                            @foreach($contenidoActual['contenido']['ejercicios'] as $index => $ejercicio)
                                                <div class="ejercicio-item" id="ejercicio_{{ $index }}">
                                                    <div class="ejercicio-header">
                                                        <span class="ejercicio-numero">{{ $index + 1 }}</span>
                                                        <button type="button" class="btn-eliminar" onclick="eliminarEjercicio('ejercicio_{{ $index }}')">❌</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Oración (usa ___ donde va la respuesta)</label>
                                                        <input type="text" class="ejercicio-oracion" value="{{ $ejercicio['oracion'] }}" placeholder="Ej: She ___ to school every day." required>
                                                        <small>Tip: Usa ___ (tres guiones bajos) para marcar el espacio a completar</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Respuesta Correcta</label>
                                                        <input type="text" class="ejercicio-respuesta" value="{{ $ejercicio['respuesta'] }}" placeholder="Ej: goes" required>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn-agregar" onclick="agregarEjercicioCompletar()">
                                        ➕ Agregar ejercicio
                                    </button>

                                @else
                                    <!-- CONTENIDO MANUAL U OTROS -->
                                    <h4>📝 Contenido Manual</h4>
                                    <div class="form-group">
                                        <label for="contenido-manual">Descripción de la actividad</label>
                                        <textarea id="contenido-manual" rows="8" placeholder="Describe aquí cómo será la actividad, sus reglas, objetivos, etc...">{{ $contenidoActual['contenido']['descripcion'] ?? '' }}</textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="form-actions">
                <button type="button" class="btn-cancelar" onclick="window.location.href='/profesores/actividades/{{ $actividad->id }}'">
                    Cancelar
                </button>
                <button type="button" class="btn-vista-previa" onclick="vistaPrevia()">
                    Vista previa
                </button>
                <button type="button" class="btn-guardar" onclick="enviarFormularioEdicion()">
                    Guardar cambios
                </button>
            </div>

            <!-- Campo oculto para el contenido -->
            <input type="hidden" id="contenido" name="contenido">
        </form>
    </div>

    <!-- JavaScript simplificado -->
    <script>
        const tipoActual = '{{ $tipoActual }}';
        let preguntasCreadas = [];
        let ejerciciosCreados = [];

        // === FUNCIONES DE IMAGEN ===
        function seleccionarImagen() {
            document.getElementById('imagen').click();
        }

        function previsualizarImagen(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                if (file.size > 2048 * 1024) {
                    alert('La imagen es muy grande. El tamaño máximo es 2MB.');
                    input.value = '';
                    return;
                }

                if (!file.type.match('image.*')) {
                    alert('Por favor selecciona un archivo de imagen válido.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagenPreview').innerHTML = 
                        `<img src="${e.target.result}" alt="Vista previa" style="max-width: 100%; height: auto; border-radius: 8px; max-height: 150px; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // === FUNCIONES PARA QUIZ ===
        function agregarPreguntaQuiz() {
            const preguntaId = 'pregunta_' + Date.now();
            const preguntaHtml = `
                <div class="pregunta-item" id="${preguntaId}">
                    <div class="pregunta-header">
                        <span class="pregunta-numero">${document.querySelectorAll('.pregunta-item').length + 1}</span>
                        <button type="button" class="btn-eliminar" onclick="eliminarPregunta('${preguntaId}')">❌</button>
                    </div>
                    <div class="form-group">
                        <label>Pregunta</label>
                        <input type="text" class="pregunta-texto" placeholder="Escribe tu pregunta aquí..." required>
                    </div>
                    <div class="opciones-grid">
                        <div class="form-group">
                            <label>Opción A</label>
                            <input type="text" class="opcion-input" data-opcion="A" placeholder="Opción A" required>
                        </div>
                        <div class="form-group">
                            <label>Opción B</label>
                            <input type="text" class="opcion-input" data-opcion="B" placeholder="Opción B" required>
                        </div>
                        <div class="form-group">
                            <label>Opción C</label>
                            <input type="text" class="opcion-input" data-opcion="C" placeholder="Opción C" required>
                        </div>
                        <div class="form-group">
                            <label>Opción D</label>
                            <input type="text" class="opcion-input" data-opcion="D" placeholder="Opción D" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Respuesta Correcta</label>
                        <select class="respuesta-correcta" required>
                            <option value="">Seleccionar...</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
            `;
            
            document.getElementById('preguntas-container').insertAdjacentHTML('beforeend', preguntaHtml);
            preguntasCreadas.push(preguntaId);
            actualizarNumeracionPreguntas();
        }

        function eliminarPregunta(preguntaId) {
            document.getElementById(preguntaId).remove();
            preguntasCreadas = preguntasCreadas.filter(id => id !== preguntaId);
            actualizarNumeracionPreguntas();
        }

        function actualizarNumeracionPreguntas() {
            document.querySelectorAll('.pregunta-item').forEach((item, index) => {
                item.querySelector('.pregunta-numero').textContent = index + 1;
            });
        }

        // === FUNCIONES PARA COMPLETAR ===
        function agregarEjercicioCompletar() {
            const ejercicioId = 'ejercicio_' + Date.now();
            const ejercicioHtml = `
                <div class="ejercicio-item" id="${ejercicioId}">
                    <div class="ejercicio-header">
                        <span class="ejercicio-numero">${document.querySelectorAll('.ejercicio-item').length + 1}</span>
                        <button type="button" class="btn-eliminar" onclick="eliminarEjercicio('${ejercicioId}')">❌</button>
                    </div>
                    <div class="form-group">
                        <label>Oración (usa ___ donde va la respuesta)</label>
                        <input type="text" class="ejercicio-oracion" placeholder="Ej: She ___ to school every day." required>
                        <small>Tip: Usa ___ (tres guiones bajos) para marcar el espacio a completar</small>
                    </div>
                    <div class="form-group">
                        <label>Respuesta Correcta</label>
                        <input type="text" class="ejercicio-respuesta" placeholder="Ej: goes" required>
                    </div>
                </div>
            `;
            
            document.getElementById('ejercicios-container').insertAdjacentHTML('beforeend', ejercicioHtml);
            ejerciciosCreados.push(ejercicioId);
            actualizarNumeracionEjercicios();
        }

        function eliminarEjercicio(ejercicioId) {
            document.getElementById(ejercicioId).remove();
            ejerciciosCreados = ejerciciosCreados.filter(id => id !== ejercicioId);
            actualizarNumeracionEjercicios();
        }

        function actualizarNumeracionEjercicios() {
            document.querySelectorAll('.ejercicio-item').forEach((item, index) => {
                item.querySelector('.ejercicio-numero').textContent = index + 1;
            });
        }

        // === RECOPILAR DATOS ===
        function recopilarDatos() {
            let contenidoData = {
                tipo: tipoActual,
                contenido: {}
            };

            if (tipoActual === 'quiz') {
                const preguntas = [];
                document.querySelectorAll('.pregunta-item').forEach(item => {
                    const pregunta = item.querySelector('.pregunta-texto').value.trim();
                    const opciones = {
                        A: item.querySelector('[data-opcion="A"]').value.trim(),
                        B: item.querySelector('[data-opcion="B"]').value.trim(),
                        C: item.querySelector('[data-opcion="C"]').value.trim(),
                        D: item.querySelector('[data-opcion="D"]').value.trim()
                    };
                    const correcta = item.querySelector('.respuesta-correcta').value;
                    
                    if (pregunta && opciones.A && opciones.B && opciones.C && opciones.D && correcta) {
                        preguntas.push({
                            pregunta: pregunta,
                            opciones: opciones,
                            correcta: correcta
                        });
                    }
                });
                contenidoData.contenido.preguntas = preguntas;
            } else if (tipoActual === 'completar') {
                const ejercicios = [];
                document.querySelectorAll('.ejercicio-item').forEach(item => {
                    const oracion = item.querySelector('.ejercicio-oracion').value.trim();
                    const respuesta = item.querySelector('.ejercicio-respuesta').value.trim();
                    
                    if (oracion && respuesta) {
                        ejercicios.push({
                            oracion: oracion,
                            respuesta: respuesta
                        });
                    }
                });
                contenidoData.contenido.ejercicios = ejercicios;
            } else {
                // Manual u otros tipos
                const descripcion = document.getElementById('contenido-manual');
                if (descripcion) {
                    contenidoData.contenido.descripcion = descripcion.value.trim();
                }
            }

            return contenidoData;
        }

        // === VISTA PREVIA ===
        function vistaPrevia() {
            const datos = recopilarDatos();
            
            let mensaje = `=== VISTA PREVIA DE LA ACTIVIDAD ===\n\n`;
            mensaje += `Tipo: ${datos.tipo.toUpperCase()}\n\n`;

            if (datos.tipo === 'quiz' && datos.contenido.preguntas.length > 0) {
                mensaje += `PREGUNTAS (${datos.contenido.preguntas.length}):\n\n`;
                datos.contenido.preguntas.forEach((p, i) => {
                    mensaje += `${i + 1}. ${p.pregunta}\n`;
                    mensaje += `   A) ${p.opciones.A}\n   B) ${p.opciones.B}\n   C) ${p.opciones.C}\n   D) ${p.opciones.D}\n`;
                    mensaje += `   Correcta: ${p.correcta}\n\n`;
                });
            } else if (datos.tipo === 'completar' && datos.contenido.ejercicios.length > 0) {
                mensaje += `EJERCICIOS (${datos.contenido.ejercicios.length}):\n\n`;
                datos.contenido.ejercicios.forEach((e, i) => {
                    mensaje += `${i + 1}. ${e.oracion}\n`;
                    mensaje += `   Respuesta: ${e.respuesta}\n\n`;
                });
            } else {
                mensaje += datos.contenido.descripcion || 'Sin contenido especificado.';
            }
            
            alert(mensaje);
        }

        // === ENVÍO DEL FORMULARIO ===
        function enviarFormularioEdicion() {
            console.log('🚀 Enviando formulario de edición...');
            
            // Recopilar datos
            const contenidoData = recopilarDatos();
            
            // Validaciones básicas
            if (tipoActual === 'quiz' && contenidoData.contenido.preguntas.length === 0) {
                alert('❌ Debes tener al menos una pregunta en el quiz');
                return;
            }
            
            if (tipoActual === 'completar' && contenidoData.contenido.ejercicios.length === 0) {
                alert('❌ Debes tener al menos un ejercicio de completar');
                return;
            }
            
            // Establecer contenido en campo oculto
            document.getElementById('contenido').value = JSON.stringify(contenidoData);
            
            console.log('✅ Contenido preparado:', contenidoData);
            
            // Enviar formulario
            document.getElementById('formEditarActividad').submit();
        }

        // === SELECTS DEPENDIENTES ===
        const parcialesPorCuatrimestre = @json($cuatrimestres->mapWithKeys(function ($c) {
            return [
                $c->id => $c->parciales->map(function ($p) {
                    return ['id' => $p->id, 'nombre' => $p->nombre];
                })->values()
            ];
        }));

        document.addEventListener('DOMContentLoaded', function () {
            const cuatrimestreSelect = document.getElementById('cuatrimestre_id');
            const parcialSelect = document.getElementById('parcial_id');

            function actualizarParciales() {
                const cuatriId = cuatrimestreSelect.value;
                parcialSelect.innerHTML = '';
                
                if (!cuatriId || !parcialesPorCuatrimestre[cuatriId]) {
                    parcialSelect.innerHTML = '<option value="">Selecciona primero el cuatrimestre...</option>';
                    parcialSelect.disabled = true;
                    return;
                }
                
                parcialSelect.disabled = false;
                parcialSelect.innerHTML = '<option value="">Seleccionar parcial...</option>';
                
                parcialesPorCuatrimestre[cuatriId].forEach(function (parcial) {
                    parcialSelect.innerHTML += `<option value="${parcial.id}">${parcial.nombre}</option>`;
                });
                
                // Seleccionar el parcial actual
                const parcialActual = '{{ old('parcial_id', $actividad->parcial_id) }}';
                if (parcialActual) {
                    parcialSelect.value = parcialActual;
                }
            }

            cuatrimestreSelect.addEventListener('change', actualizarParciales);

            // Inicializar al cargar
            if (cuatrimestreSelect.value) {
                actualizarParciales();
            }
        });
    </script>

@endsection