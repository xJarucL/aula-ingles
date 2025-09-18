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
    @include('partials.toast')
    <!-- Header -->
<header>
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

            @if (request()->routeIs('profesores.login') || request()->routeIs('alumnos.ingresar') || request()->is('alumnos/ingresar'))
                <a href="{{ url('/') }}" class="login-button-mobile">Volver</a>
            @else
                <a href="{{ route('profesores.login') }}" class="login-button-mobile">Inicio de sesión</a>
            @endif
        </nav>
    </div>

    @if (request()->routeIs('profesores.login') || request()->routeIs('alumnos.ingresar') || request()->is('alumnos/ingresar'))
        <a href="{{ url('/') }}" class="login-button login-button-desktop">Volver</a>
    @else
        <a href="{{ route('profesores.login') }}" class="login-button login-button-desktop">Inicio de sesión</a>
    @endif

    {{-- debug silencioso para verificar ruta actual; bórralo luego --}}
    {{-- route: {{ optional(request()->route())->getName() }} | path: {{ request()->path() }} --}}
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