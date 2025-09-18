<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Panel Docente' }} - UTEnglish</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>
    @include('partials.toast')
    <!-- Navbar del panel para profesores -->
    <div class="panel-navbar">
        <div class="panel-navbar-top">
            <div class="panel-navbar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <div class="panel-navbar-links">
                    <a href="{{ route('profesores.panel') }}">Mi panel de control</a>
                </div>
            </div>

            <div class="panel-navbar-icons">
                
                <a href="{{ route('notificaciones') }}" class="panel-icon" title="Notificaciones">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </a>
                <div class="panel-dropdown">
                    <span class="panel-dropdown-icon" onclick="toggleDropdown()" title="Mi cuenta">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <div id="userDropdown" class="panel-dropdown-menu">
                        <div style="padding:8px 12px; font-size:0.95rem; color:#333;">
                            <strong>{{ Auth::user()->name ?? 'Usuario' }}</strong><br>
                            <small>Rol: {{ Auth::user()->rol ?? 'docente' }}</small>
                        </div>
                        <hr style="margin:6px 0; border-color:#eee;">
                        <a href="{{ route('perfil') }}">Mi cuenta</a>

                        {{-- Opciones adicionales para admin (si aplica) --}}
                        @php
                            $isAdmin = (Auth::user()->rol ?? '') === 'admin';
                            // Fallback seguro: si la ruta nombrada no existe, usar URL directa
                            $adminUrl = \Illuminate\Support\Facades\Route::has('admin.profesores.index') ? route('admin.profesores.index') : url('/admin/profesores');
                        @endphp
                        @if($isAdmin)
                            <a href="{{ $adminUrl }}" target="_blank">Administrar profesores</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="panel-search-mini" id="miniSearchBox">
                <input type="text" id="miniSearchInput" placeholder="Buscar trabajos...">
            </div>
        </div>
    </div>

    <!-- Sub-navbar -->
    <div class="panel-sub-navbar">
        <a href="{{ route('trabajos.index') }}" class="{{ request()->routeIs('trabajos.*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14,2 14,8 20,8"></polyline>
            </svg>
            Mis trabajos
        </a>
        <a href="{{ route('profesores.estudiantes') }}"
            class="{{ request()->routeIs('profesores.estudiantes') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Mis estudiantes
        </a>

        <a href="{{ route('profesores.actividades') }}"
            class="{{ request()->routeIs('profesores.actividades*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                <circle cx="10" cy="8" r="2"></circle>
                <path d="M8 12h4"></path>
                <path d="M8 16h4"></path>
            </svg>
            Actividades
        </a>
    </div>

    <!-- Contenido principal -->
    <main class="panel-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("userDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        window.onclick = function (e) {
            if (!e.target.closest('.panel-dropdown')) {
                const dropdown = document.getElementById("userDropdown");
                if (dropdown && dropdown.style.display === "block") {
                    dropdown.style.display = "none";
                }
            }
        };

        function mostrarBuscador() {
            const box = document.getElementById("miniSearchBox");
            box.style.display = box.style.display === "block" ? "none" : "block";
            if (box.style.display === "block") {
                document.getElementById("miniSearchInput").focus();
            }
        }
    </script>
</body>

</html>