@extends('layouts.alumno')

@section('title', 'Subir Tarea')

@section('content')
<div class="container">
    <!-- Título -->
    <div class="page-title">
        <h1>📤 Subir Tarea</h1>
        <p>Comparte tu trabajo y recibe retroalimentación de tus profesores</p>
    </div>

    <!-- Navegación -->
    <div class="nav-buttons">
        <a href="{{ route('alumnos.panel') }}" class="btn btn-secondary">
            ← Volver al Inicio
        </a>
        <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-primary">
            📚 Mis Grupos
        </a>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">✅</div>
            <div class="alert-content">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <div class="alert-icon">❌</div>
            <div class="alert-content">{{ session('error') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="alert-icon">⚠️</div>
            <div class="alert-content">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Formulario de subida -->
    <div class="upload-card">
        <div class="upload-header">
            <h2>Nueva Tarea</h2>
            <p>Completa la información y sube los archivos de tu tarea</p>
        </div>

        <!-- Requisitos -->
        <div class="requirements">
            <h4>📋 Requisitos para la entrega:</h4>
            <ul>
                <li>Archivos permitidos: PDF, DOC, DOCX, TXT, JPG, PNG</li>
                <li>Tamaño máximo por archivo: 10 MB</li>
                <li>Puedes subir múltiples archivos</li>
                <li>Incluye una descripción clara de tu trabajo</li>
            </ul>
        </div>

        <!-- Formulario -->
        <form action="{{ route('alumnos.procesar-tarea') }}" method="POST" enctype="multipart/form-data" class="upload-form" onsubmit="mostrarCargando()">
            @csrf

            <div class="form-section">
                <h3>📝 Información de la Tarea</h3>
                
                <div class="form-group">
                    <label for="titulo">Título de la Tarea *</label>
                    <input 
                        type="text" 
                        id="titulo" 
                        name="titulo" 
                        placeholder="Ej: Ensayo sobre Present Simple"
                        value="{{ old('titulo') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea 
                        id="descripcion" 
                        name="descripcion" 
                        placeholder="Describe brevemente tu trabajo..."
                        rows="4"
                    >{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="materia">Materia</label>
                        <select id="materia" name="materia">
                            <option value="">Selecciona una materia</option>
                            <option value="ingles" {{ old('materia') == 'ingles' ? 'selected' : '' }}>Inglés</option>
                            <option value="frances" {{ old('materia') == 'frances' ? 'selected' : '' }}>Francés</option>
                            <option value="aleman" {{ old('materia') == 'aleman' ? 'selected' : '' }}>Alemán</option>
                            <option value="otro" {{ old('materia') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="cuatrimestre">Cuatrimestre</label>
                        <select id="cuatrimestre" name="cuatrimestre">
                            <option value="">Selecciona cuatrimestre</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('cuatrimestre') == $i ? 'selected' : '' }}>
                                    {{ $i }}° Cuatrimestre
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>📎 Archivos</h3>
                
                <div class="file-upload-area" onclick="document.getElementById('archivo').click()">
                    <div class="upload-icon">📁</div>
                    <div class="upload-text">
                        <p><strong>Haz clic para seleccionar archivos</strong></p>
                        <small>O arrastra y suelta tus archivos aquí</small>
                    </div>
                    <input 
                        type="file" 
                        id="archivo" 
                        name="archivo" 
                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"
                        style="display: none;"
                        onchange="mostrarArchivos(this)"
                        required
                    >
                </div>
                
                <div id="archivos-seleccionados" class="selected-files"></div>
            </div>

            <div class="form-actions">
                <button type="button" onclick="limpiarFormulario()" class="btn-secondary">
                    🗑️ Limpiar
                </button>
                <button type="submit" id="submitBtn" class="btn-primary">
                    <span class="btn-text">📤 Subir Tarea</span>
                    <span class="btn-loader" style="display: none;">⏳ Subiendo...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Historial de tareas -->
    <div class="history-section">
        <h3>📚 Mis Tareas Anteriores</h3>
        <div class="history-placeholder">
            <p>Aquí aparecerán tus tareas anteriores una vez que subas tu primera tarea.</p>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 800px;
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
    line-height: 1.5;
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
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #1e847d 0%, #2a9d8f 100%);
    color: white;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(30, 132, 125, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid;
}

.alert-success {
    background: #d4edda;
    border-left-color: #28a745;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    border-left-color: #dc3545;
    color: #721c24;
}

.alert-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.upload-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.upload-header {
    text-align: center;
    margin-bottom: 2rem;
}

.upload-header h2 {
    color: #1e847d;
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.requirements {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    border-left: 4px solid #1e847d;
}

.requirements h4 {
    color: #1e847d;
    margin-bottom: 1rem;
}

.requirements ul {
    margin: 0;
    padding-left: 1.5rem;
}

.requirements li {
    margin-bottom: 0.5rem;
    color: #555;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section h3 {
    color: #1e847d;
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fafafa;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #1e847d;
    background: white;
    box-shadow: 0 0 0 3px rgba(30, 132, 125, 0.1);
}

.file-upload-area {
    border: 2px dashed #ccc;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.file-upload-area:hover {
    border-color: #1e847d;
    background: #f0f8f6;
}

.upload-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #666;
}

.upload-text p {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.1rem;
}

.upload-text small {
    color: #666;
}

.selected-files {
    margin-top: 1rem;
}

.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #e8f5e8;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.history-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.history-section h3 {
    color: #1e847d;
    margin-bottom: 1.5rem;
}

.history-placeholder {
    text-align: center;
    color: #666;
    font-style: italic;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .nav-buttons {
        flex-direction: column;
    }
}
</style>

<script>
function mostrarArchivos(input) {
    const container = document.getElementById('archivos-seleccionados');
    container.innerHTML = '';
    
    if (input.files.length > 0) {
        const file = input.files[0];
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `
            <div class="file-info">
                <span>📄</span>
                <span>${file.name}</span>
                <span style="color: #666; font-size: 0.9rem;">(${formatFileSize(file.size)})</span>
            </div>
            <button type="button" onclick="removerArchivo()" style="background: none; border: none; color: #dc3545; font-size: 1.2rem; cursor: pointer;">❌</button>
        `;
        container.appendChild(fileItem);
    }
}

function removerArchivo() {
    document.getElementById('archivo').value = '';
    document.getElementById('archivos-seleccionados').innerHTML = '';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function mostrarCargando() {
    const btn = document.getElementById('submitBtn');
    const btnText = btn.querySelector('.btn-text');
    const btnLoader = btn.querySelector('.btn-loader');
    
    btnText.style.display = 'none';
    btnLoader.style.display = 'inline';
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.style.cursor = 'not-allowed';
}

function limpiarFormulario() {
    if (confirm('¿Estás seguro de que deseas limpiar el formulario?')) {
        document.querySelector('.upload-form').reset();
        document.getElementById('archivos-seleccionados').innerHTML = '';
    }
}

// Drag and drop functionality
const uploadArea = document.querySelector('.file-upload-area');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.style.borderColor = '#1e847d';
    uploadArea.style.background = '#f0f8f6';
}

function unhighlight(e) {
    uploadArea.style.borderColor = '#ccc';
    uploadArea.style.background = '#f8f9fa';
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        document.getElementById('archivo').files = files;
        mostrarArchivos(document.getElementById('archivo'));
    }
}

// Auto-hide alerts
window.addEventListener('load', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>
@endsection