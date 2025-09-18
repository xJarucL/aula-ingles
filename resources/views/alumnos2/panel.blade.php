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

    .logo img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
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

    .welcome-section {
        text-align: center;
        margin: 3rem 0;
    }

    .welcome-title {
        color: #1e847d;
        font-size: 2rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .student-info {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    .student-info h3 {
        color: #1e847d;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .student-details {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .student-details p {
        margin: 0;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
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

    .btn-primary {
        background: #1e847d;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .exam-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        max-width: 560px;
        margin: 1.5rem auto;
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

    .question-section {
        margin-bottom: 3rem;
    }

    .question-title {
        color: #1e847d;
        font-size: 1.3rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .question-text {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
        color: #333;
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
        padding: 1rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }

    .option:hover {
        border-color: #1e847d;
        background: #f8fffe;
    }

    .option input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: #1e847d;
    }

    .option label {
        cursor: pointer;
        font-size: 1rem;
        flex: 1;
        font-weight: 500;
    }

    .next-btn {
        background: linear-gradient(135deg, #7dd3c0 0%, #1e847d 100%);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: block;
        margin: 2rem auto 0;
    }

    .next-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 132, 125, 0.3);
    }


    .temp-login {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }

    .temp-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    }

    .form-group {
        margin-bottom: 1.5rem;
        text-align: left;
    }

    .form-group label {
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #1e847d;
        box-shadow: 0 0 0 3px rgba(30, 132, 125, 0.1);
    }

    .divider {
        text-align: center;
        margin: 1.5rem 0;
        color: #666;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #ddd;
        z-index: 1;
    }

    .divider span {
        background: white;
        padding: 0 1rem;
        position: relative;
        z-index: 2;
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

        .welcome-title {
            font-size: 2rem;
        }

        .exam-container {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .student-details {
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

    /* floating-upload styles removed */
    }
</style>



<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('error'))
        <div class="alert alert-error">
            {{ Session::get('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Bienvenida -->
    <div class="welcome-section">
        <h1 class="welcome-title">Bienvenido a la plataforma de actividades</h1>
    </div>

    <!-- Información del estudiante si ya está logueado -->
    @if(Session::has('alumno_datos'))
        @php
            $alumno = Session::get('alumno_datos');
        @endphp
        <div class="student-info">
            <h3>¡Hola {{ $alumno['nombre'] }}!</h3>
            <div class="student-details">
                <p><strong>Matrícula:</strong> {{ $alumno['matricula'] }}</p>
                <p><strong>Carrera:</strong> {{ $alumno['carrera'] }}</p>
                <p><strong>Cuatrimestre:</strong> {{ $alumno['cuatrimestre'] }}°</p>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('alumnos.mis-grupos') }}" class="btn btn-primary">
                    📚 Mis Grupos
                </a>
                <a href="{{ route('alumnos.historial') }}" class="btn btn-secondary">
                    📊 Mi Historial
                </a>
                <a href="{{ route('alumnos.cerrar-sesion') }}" class="btn btn-danger">
                    🚪 Cerrar Sesión
                </a>
            </div>
        </div>
    @endif

    <!-- Formulario SOLO si NO está logueado -->
    @if(!Session::has('alumno_datos'))
        <div class="exam-container">
            <h2 class="exam-title">Inicio de sesión</h2>
            
            
            
            
            <form action="{{ route('alumnos.procesar') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="matricula">Matrícula del estudiante</label>
                    <input type="text" name="matricula" id="matricula" placeholder="Ingresa tu matrícula" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Contraseña" required>
                </div>

                <!-- Ahora solo se requiere matrícula y contraseña; carrera/cuatrimestre/parcial se obtienen desde la cuenta -->

                <button type="submit" class="next-btn">
                    Acceder a mis materias
                </button>
            </form>
        </div>
    @endif

</div>

<script>
// Login temporal para pruebas
function loginTemporalEstudiante() {
    // Por ahora solo prellenar el formulario con datos de ejemplo
    if (document.getElementById('matricula')) {
        document.getElementById('matricula').value = '2025001234';
        document.getElementById('carrera_id').value = '1';
        document.getElementById('cuatrimestre').value = '1';
        
        alert('✅ Datos de prueba cargados\n\nMatrícula: 2025001234\nCarrera: TICS\nCuatrimestre: 1°\n\nAhora haz clic en "Acceder a mis materias"');
    }
}

// Manejar cambio de carrera
document.addEventListener('DOMContentLoaded', function() {
    const carreraSelect = document.getElementById('carrera_id');
    const cuatrimestreSelect = document.getElementById('cuatrimestre');
    
    if (carreraSelect && cuatrimestreSelect) {
        carreraSelect.addEventListener('change', function() {
            cuatrimestreSelect.disabled = !this.value;
        });
    }
});
</script>
@endsection