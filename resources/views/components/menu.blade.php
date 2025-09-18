{{-- resources/views/components/menu.blade.php --}}
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
            <a href="{{ url('/alumnos/ingresar') }}">Alumnos</a>
            <a href="{{ url('/reconocimiento') }}">Prueba de reconocimiento de voz a texto</a>
            <a href="{{ url('/audio_subtitles') }}">Prueba de audio y subtitulos</a>
            <a href="{{ url('/profesores/login') }}" class="login-button-mobile">Inicio de sesión</a>
        </nav>
    </div>
    <a href="{{ url('/profesores/login') }}" class="login-button login-button-desktop">Inicio de sesión</a>
</header>

<script>
document.getElementById('menu-toggle').addEventListener('click', function() {
    document.getElementById('mobile-nav').classList.toggle('active');
    this.classList.toggle('active');
});
</script>