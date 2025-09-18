@extends('layouts.panel')

@section('title', 'Crear Actividad')

@section('content')
    <div class="crear-actividad-container">
        <!-- Header -->
        <div class="crear-actividad-header">
            <h1>Crear nueva actividad</h1>
            <a href="{{ route('profesores.actividades') }}" class="btn-volver-panel">
                Volver al panel de actividades
            </a>
        </div>

        <!-- Alertas -->
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

        @if (session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Selector de Modo -->
        <div class="modo-selector">
            <div class="modo-botones">
                <button class="modo-btn active" data-modo="crear">
                    ➕ Crear nueva actividad
                </button>
                <button class="modo-btn" data-modo="importar">
                    📄 Importar archivo
                </button>
            </div>
        </div>

        <!-- ⚠️ FORMULARIO SIN ATRIBUTOS name DUPLICADOS -->
        <div class="crear-form" id="formActividad">
            @csrf

            <!-- Modo: Crear Nueva Actividad -->
            <div id="modo-crear" class="modo-contenido active">
                <div class="form-layout">
                    <!-- Columna Izquierda: Información Básica -->
                    <div class="columna-izquierda">
                        <div class="seccion-card">
                            <h3>📝 Información básica</h3>

                            <div class="form-group">
                                <label for="nombre">Nombre de la actividad</label>
                                <input type="text" id="nombre" value="{{ old('nombre') }}"
                                    placeholder="Ej: Present Simple Practice" required>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" rows="3"
                                    placeholder="Describe brevemente de qué trata esta actividad...">{{ old('descripcion') }}</textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cuatrimestre_id">Cuatrimestre</label>
                                    <select id="cuatrimestre_id" required>
                                        <option value="">Seleccionar cuatrimestre...</option>
                                        @foreach($cuatrimestres as $cuatri)
                                            <option value="{{ $cuatri->id }}" {{ old('cuatrimestre_id') == $cuatri->id ? 'selected' : '' }}>
                                                {{ $cuatri->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="parcial_id">Parcial</label>
                                    <select id="parcial_id" required disabled>
                                        <option value="">Selecciona primero el cuatrimestre...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Imagen de la actividad</label>
                                <div class="imagen-upload-area" onclick="seleccionarImagen()">
                                    <div class="imagen-preview" id="imagenPreview">
                                        <div class="placeholder-image">
                                            📸
                                            <p>Arrastra una imagen aquí o haz click para seleccionar una imagen</p>
                                            <small>PNG, JPG, GIF hasta 2MB</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" id="imagen" accept="image/*" style="display: none;"
                                    onchange="previsualizarImagen(this)">
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Para modo crear NO debe tener importar archivo -->
                    <div class="columna-derecha">
                        <div class="seccion-card">
                            <h3>✨ Actividad manual</h3>
                            <p class="crear-descripcion">
                                Cuando presiones "Crear actividad", podrás agregar preguntas manualmente una por una.
                            </p>
                            <div class="manual-info">
                                <div class="info-item">
                                    <span class="info-icon">📝</span>
                                    <span>Agrega preguntas de múltiple opción, verdadero/falso, y más</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-icon">🎯</span>
                                    <span>Control total sobre cada pregunta y respuesta</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-icon">⏰</span>
                                    <span>Configura tiempos y puntuaciones personalizadas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <button type="button" class="btn-cancelar"
                        onclick="window.location.href='{{ route('profesores.actividades') }}'">
                        Cancelar
                    </button>
                    <button type="button" class="btn-vista-previa" onclick="vistaPrevia()">
                        Vista previa
                    </button>
                    <button type="button" class="btn-crear" onclick="enviarFormularioManual()">
                        Crear actividad
                    </button>
                </div>
            </div>

            <!-- Modo: Importar Archivo -->
            <div id="modo-importar" class="modo-contenido">
                <div class="form-layout">
                    <!-- Columna Izquierda: Información Básica -->
                    <div class="columna-izquierda">
                        <div class="seccion-card">
                            <h3>📝 Información básica</h3>

                            <div class="form-group">
                                <label for="nombre_pdf">Nombre de la actividad</label>
                                <input type="text" id="nombre_pdf" placeholder="Ej: Present Simple Practice" required>
                            </div>

                            <div class="form-group">
                                <label for="descripcion_pdf">Descripción</label>
                                <textarea id="descripcion_pdf" rows="3"
                                    placeholder="Describe brevemente de qué trata esta actividad..."></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cuatrimestre_id_pdf">Cuatrimestre</label>
                                    <select id="cuatrimestre_id_pdf" required>
                                        <option value="">Seleccionar cuatrimestre...</option>
                                        @foreach($cuatrimestres as $cuatri)
                                            <option value="{{ $cuatri->id }}">{{ $cuatri->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="parcial_id_pdf">Parcial</label>
                                    <select id="parcial_id_pdf" required disabled>
                                        <option value="">Selecciona primero el cuatrimestre...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Imagen de la actividad</label>
                                <div class="imagen-upload-area" onclick="seleccionarImagenPdf()">
                                    <div class="imagen-preview" id="imagenPreviewPdf">
                                        <div class="placeholder-image">
                                            📸
                                            <p>Arrastra una imagen aquí o haz click para seleccionar una imagen</p>
                                            <small>PNG, JPG, GIF hasta 2MB</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" id="imagen_pdf" accept="image/*" style="display: none;"
                                    onchange="previsualizarImagenPdf(this)">
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Importar Archivo (SOLO en modo importar) -->
                    <div class="columna-derecha">
                        <div class="seccion-card">
                            <h3>📄 Importar archivo de preguntas</h3>
                            <p class="importar-descripcion">
                                Sube un archivo PDF, GIFT, XML, XHTML o TXT y extraeremos automáticamente las preguntas para
                                crear tu actividad
                            </p>

                            <div class="pdf-upload-zone" id="pdfUploadZoneImportar">
                                <div class="pdf-preview" id="pdfPreviewImportar">
                                    <div class="placeholder-pdf">
                                        <div style="font-size: 48px; margin-bottom: 16px;">📄</div>
                                        <p>Arrastra un archivo aquí o haz click para seleccionar</p>
                                        <small>PDF, GIFT, XML, XHTML, TXT | Máx 10MB</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Input file con ID único -->
                            <input type="file" id="archivoPreguntasImportar"
                                accept=".pdf,.gift,.xml,.xhtml,.html,.txt,application/pdf,text/xml,text/html,text/plain"
                                style="display: none;">

                            <!-- Información del archivo seleccionado -->
                            <div id="archivoInfoImportar" class="archivo-info" style="display: none;">
                                <div class="archivo-info-content">
                                    <strong>📁 Archivo seleccionado:</strong>
                                    <div id="archivoNombreImportar"></div>
                                    <div id="archivoTamañoImportar"></div>
                                </div>
                            </div>

                            <!-- Botón para procesar -->
                            <div class="pdf-actions">
                                <button type="button" class="btn-visualizar" id="btnProcesarArchivoImportar"
                                    onclick="procesarArchivoSeleccionadoImportar()" style="display: none;">
                                    👁️ Procesar archivo
                                </button>
                            </div>

                            <!-- Progress -->
                            <div id="pdfProgressImportar" class="pdf-progress" style="display: none;">
                                <div class="progress-bar">
                                    <div class="progress-fill" id="progressFillImportar"></div>
                                </div>
                                <span class="progress-text" id="progressTextImportar">Procesando archivo...</span>
                            </div>

                            <!-- Resultado -->
                            <div id="pdfResultadoImportar" class="pdf-resultado" style="display: none;">
                                <!-- Aquí se mostrará el resultado del procesamiento -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción para PDF/Archivo -->
                <div class="form-actions">
                    <button type="button" class="btn-cancelar"
                        onclick="window.location.href='{{ route('profesores.actividades') }}'">
                        Cancelar
                    </button>
                    <button type="button" class="btn-crear" id="btnCrearPDF" style="display: none;">
                        Crear actividad
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @include('scripts.crear-actividad-scripts')
@endpush