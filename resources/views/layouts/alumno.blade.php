<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'UTEnglish' }} - UTEnglish</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Header -->
   <header>
    @if(session('alumno_datos'))
        {{-- Alumno autenticado: deja el header tal cual --}}
        <div class="header-left">
            <a href="{{ url('/') }}" class="logo-link">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                <h1>UTEnglish</h1>
            </a>

            <button id="menu-toggle" class="menu-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            @php
                $parcialId = session('parcial_id', 1);
            @endphp

            <nav id="mobile-nav" class="mobile-nav">
                <a href="{{ route('alumnos.panel') }}">Inicio</a>
                <a href="{{ route('alumnos.actividades', ['parcialId' => $parcialId]) }}">Actividades</a>
                <a href="{{ route('alumnos.historial') }}">Historial</a>
            </nav>
        </div>

        <div class="logout-desktop">
            <a href="{{ route('alumnos.cerrar-sesion') }}" class="logout-button">Cerrar Sesión</a>
        </div>
    @else
        @php
            // Pantallas donde el botón debe decir "Volver"
            $isAuthScreen = request()->routeIs('alumnos.ingresar')
                || request()->routeIs('profesores.login')
                || request()->is('alumnos/ingresar'); // por si el nombre de ruta cambia pero el path no
        @endphp

        <div class="header-left">
            <a href="{{ url('/') }}" class="logo-link">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                <h1>UTEnglish</h1>
            </a>

            <button id="menu-toggle" class="menu-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <nav id="mobile-nav" class="mobile-nav">
                <a href="{{ route('alumnos.ingresar') }}">Alumnos</a>
                <a href="{{ url('/reconocimiento') }}">Prueba de reconocimiento de voz a texto</a>

                @if ($isAuthScreen)
                    <a href="{{ url('/') }}" class="login-button-mobile">Volver</a>
                @else
                    <a href="{{ route('profesores.login') }}" class="login-button-mobile">Inicio de sesión</a>
                @endif
            </nav>
        </div>

        @if ($isAuthScreen)
            <a href="{{ url('/') }}" class="login-button login-button-desktop">Volver</a>
        @else
            <a href="{{ route('profesores.login') }}" class="login-button login-button-desktop">Inicio de sesión</a>
        @endif
    @endif
</header>


    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('mobile-nav').classList.toggle('active');
            this.classList.toggle('active');
        });
    </script>
</body>
</html>
