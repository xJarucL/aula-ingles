@extends('layouts.app')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
        min-height: 100vh;
    }

    /* header styles removed to use global navbar from layouts.app */

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
    }

    .welcome-section {
        text-align: center;
        margin: 2rem 0;
    }

    .welcome-title {
        color: #1e847d;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .form-container {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    .student-form {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 450px;
        text-align: center;
    }

    .student-form h3 {
        color: #1e847d;
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .student-form p {
        color: #666;
        margin-bottom: 2rem;
        line-height: 1.5;
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

    .submit-btn {
        background: linear-gradient(135deg, #1e847d 0%, #16a2a0 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        margin-top: 1rem;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 132, 125, 0.3);
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

        .student-form {
            padding: 1.5rem;
        }

    /* floating-upload styles removed */
    }
</style>



<div class="container">
    <!-- Bienvenida -->
    <div class="welcome-section">
        <h1 class="welcome-title">Acceso a Actividades</h1>
    </div>
    
    <!-- Formulario de ingreso -->
    <div class="form-container">
        <div class="student-form">
            <h3>Datos del estudiante</h3>
            <p>Completa la información para acceder a la actividad</p>
            
            <!-- CORREGIDO: Usar la ruta correcta -->
            <form action="{{ route('alumnos.procesar') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="matricula">Matrícula</label>
                    <input type="text" name="matricula" id="matricula" placeholder="Ej: 2025001234" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Contraseña" required>
                </div>

                <!-- Solo se pide matrícula y contraseña; el resto viene de la cuenta -->

                <button type="submit" class="submit-btn">
                    Entrar a la actividad
                </button>
            </form>
        </div>
    </div>

</div>
@endsection