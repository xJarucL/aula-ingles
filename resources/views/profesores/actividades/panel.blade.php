@extends('layouts.panel')

@section('title', 'Actividades')

@section('content')
    <div class="actividades-layout">
        <!-- Sidebar Cuatrimestres -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <button class="menu-toggle-btn" id="menuToggle">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                <span class="sidebar-title">Cuatrimestres</span>
            </div>
            <div class="sidebar-content">
                @foreach($cuatrimestres as $cuatri)
                    <div class="cuatrimestre-item">
                        <div class="cuatrimestre-header" onclick="toggleCuatrimestre({{ $cuatri->id }})">
                            <span>{{ $cuatri->nombre }}</span>
                            <i class="arrow-icon">▼</i>
                        </div>
                        <div class="parciales-list" id="cuatri-{{ $cuatri->id }}" style="display: none;">
                            @foreach($cuatri->parciales as $parcial)
                                <div class="parcial-item" 
                                     onclick="filtrarActividades({{ $cuatri->id }}, {{ $parcial->id }}, '{{ addslashes($cuatri->nombre) }}', '{{ addslashes($parcial->nombre) }}')"
                                     data-cuatrimestre-id="{{ $cuatri->id }}"
                                     data-parcial-id="{{ $parcial->id }}"
                                     data-nombre-cuatrimestre="{{ $cuatri->nombre }}" 
                                     data-nombre-parcial="{{ $parcial->nombre }}">
                                    {{ $parcial->nombre }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Área Principal de Actividades -->
        <div class="actividades-main" id="actividadesMain">
            <div class="actividades-header">
                <h1 id="tituloSeccion">
                    @if($parcialSeleccionado)
                        Actividades de {{ $parcialSeleccionado->nombre }} para {{ $parcialSeleccionado->cuatrimestre->nombre }}
                    @else
                        Selecciona un cuatrimestre y un parcial
                    @endif
                </h1>
                <button class="btn-crear-actividad"
                    onclick="window.location.href='{{ route('profesores.actividades.crear') }}'">
                    Crear nueva actividad
                </button>
            </div>

            <!-- Grid de Actividades -->
            <div class="actividades-grid" id="actividadesGrid">
                @if($parcialSeleccionado)
                    @include('partials.actividades_grid', ['actividades' => $actividades])
                @else
                    <div class="sin-actividades-inicial">
                        <div class="sin-actividades-icon">📚</div>
                        <h2>Bienvenido al panel de actividades</h2>
                        <p>Selecciona un cuatrimestre y parcial del menú lateral para ver las actividades</p>
                        <div class="instrucciones">
                            <p>👈 Usa el menú lateral para navegar</p>
                            <p>➕ Crea nuevas actividades con el botón verde</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        console.log('🚀 Script de actividades cargado - V2');

        let sidebarOpen = true;
        let currentCuatriId = null;
        let currentParcialId = null;
        let currentCuatriNombre = null;
        let currentParcialNombre = null;

        // Sidebar toggle
        document.getElementById('menuToggle').addEventListener('click', function () {
            console.log('🔄 Toggle sidebar');
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('actividadesMain');
            sidebarOpen = !sidebarOpen;
            sidebar.classList.toggle('sidebar-collapsed');
            main.classList.toggle('main-expanded');

            const lines = this.querySelectorAll('.hamburger-line');
            if (!sidebarOpen) {
                lines[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                lines[1].style.opacity = '0';
                lines[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            } else {
                lines[0].style.transform = 'none';
                lines[1].style.opacity = '1';
                lines[2].style.transform = 'none';
            }
        });

        // Mostrar/ocultar parciales de un cuatrimestre
        function toggleCuatrimestre(cuatriId) {
            console.log('📂 Toggle cuatrimestre:', cuatriId);
            const parciales = document.getElementById(`cuatri-${cuatriId}`);
            const arrow = parciales.parentElement.querySelector('.arrow-icon');

            if (parciales.style.display === 'none' || parciales.style.display === '') {
                // Cerrar otros cuatrimestres abiertos
                document.querySelectorAll('.parciales-list').forEach(lista => {
                    if (lista.id !== `cuatri-${cuatriId}`) {
                        lista.style.display = 'none';
                        const otherArrow = lista.parentElement.querySelector('.arrow-icon');
                        if (otherArrow) otherArrow.style.transform = 'rotate(0deg)';
                    }
                });

                parciales.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                parciales.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Filtrado de actividades con logs
        function filtrarActividades(cuatriId, parcialId, nombreCuatri, nombreParcial) {
            console.log('🎯 Filtrar actividades:', {
                cuatriId, 
                parcialId, 
                nombreCuatri, 
                nombreParcial
            });

            // Guardar en variables globales
            currentCuatriId = cuatriId;
            currentParcialId = parcialId;
            currentCuatriNombre = nombreCuatri;
            currentParcialNombre = nombreParcial;

            // Mostrar loading
            document.getElementById('actividadesGrid').innerHTML = `
                <div class="loading-actividades">
                    <div class="loading-icon">⏳</div>
                    <p>Cargando actividades...</p>
                    <small style="color: #666;">Parcial ID: ${parcialId}</small>
                </div>
            `;

            // Cambiar título inmediatamente
            document.getElementById('tituloSeccion').textContent = 
                `Actividades de ${nombreParcial} para ${nombreCuatri}`;

            // Realizar la petición AJAX
            console.log('📡 Iniciando petición AJAX...');
            realizarPeticionActividades(cuatriId, parcialId, nombreCuatri, nombreParcial);

            // Marca el parcial activo
            document.querySelectorAll('.parcial-item').forEach(item => item.classList.remove('active'));
            const parcialElement = document.querySelector(`[data-cuatrimestre-id="${cuatriId}"][data-parcial-id="${parcialId}"]`);
            if (parcialElement) {
                parcialElement.classList.add('active');
                console.log('✅ Parcial marcado como activo');
            } else {
                console.warn('⚠️ No se encontró el elemento del parcial para marcar como activo');
            }
        }

        // Función AJAX con logs detallados
        function realizarPeticionActividades(cuatriId, parcialId, nombreCuatri, nombreParcial) {
            const url = `/profesores/actividades/filtrar?parcial_id=${parcialId}`;
            console.log('🌐 URL de petición:', url);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                console.log('📥 Respuesta recibida:', response.status, response.statusText);
                
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('📦 Datos recibidos:', data);
                
                if (data.success === false) {
                    throw new Error(data.message || 'Error desconocido del servidor');
                }
                
                if (data.html) {
                    document.getElementById('actividadesGrid').innerHTML = data.html;
                    console.log(`✅ ${data.count || 0} actividades cargadas para ${data.parcial_nombre || nombreParcial}`);
                } else {
                    document.getElementById('actividadesGrid').innerHTML = `
                        <div class="sin-actividades">
                            <div class="sin-actividades-icon">📚</div>
                            <h2>No hay actividades para este parcial</h2>
                            <p>Crea una nueva actividad para ${nombreCuatri} - ${nombreParcial}</p>
                            <button class="btn-crear-primera" onclick="window.location.href='{{ route('profesores.actividades.crear') }}'">
                                Crear Actividad
                            </button>
                        </div>
                    `;
                    console.log('ℹ️ No hay actividades para mostrar');
                }
            })
            .catch(error => {
                console.error('❌ Error en petición:', error);
                
                document.getElementById('actividadesGrid').innerHTML = `
                    <div class="error-actividades">
                        <div class="error-icon">❌</div>
                        <h2>Error al cargar actividades</h2>
                        <p>Error: ${error.message}</p>
                        <small style="color: #666; margin-top: 10px; display: block;">
                            URL: ${url}
                        </small>
                        <button class="btn-reintentar" onclick="reintentarCarga()" style="margin-top: 15px;">
                            Reintentar
                        </button>
                    </div>
                `;
            });
        }

        // Función específica para reintentar
        function reintentarCarga() {
            console.log('🔄 Reintentando carga...');
            if (currentCuatriId && currentParcialId && currentCuatriNombre && currentParcialNombre) {
                filtrarActividades(currentCuatriId, currentParcialId, currentCuatriNombre, currentParcialNombre);
            } else {
                console.error('❌ No hay información del filtro actual para reintentar');
                location.reload();
            }
        }

        // Funciones de acciones
        function verActividad(id) {
            console.log('👁️ Ver actividad:', id);
            window.location.href = `/profesores/actividades/${id}`;

        }

        function editarActividad(id) {
            console.log('✏️ Editar actividad:', id);
            window.location.href = `/profesores/actividades/${id}/editar`;
        }

        function eliminarActividad(id) {
            console.log('🗑️ Eliminar actividad:', id);
            if (confirm('¿Estás seguro de que quieres eliminar esta actividad?')) {
                fetch(`/profesores/actividades/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (currentCuatriId && currentParcialId && currentCuatriNombre && currentParcialNombre) {
                            filtrarActividades(currentCuatriId, currentParcialId, currentCuatriNombre, currentParcialNombre);
                        } else {
                            location.reload();
                        }
                    } else {
                        alert('Error al eliminar la actividad: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la actividad');
                });
            }
        }

        // Inicialización con logs
        document.addEventListener('DOMContentLoaded', function () {
            console.log('🎬 DOM cargado, inicializando...');
            
            @if($parcialSeleccionado)
                currentCuatriId = {{ $parcialSeleccionado->cuatrimestre->id }};
                currentParcialId = {{ $parcialSeleccionado->id }};
                currentCuatriNombre = '{{ addslashes($parcialSeleccionado->cuatrimestre->nombre) }}';
                currentParcialNombre = '{{ addslashes($parcialSeleccionado->nombre) }}';
                
                console.log('🎯 Parcial preseleccionado:', {
                    currentCuatriId,
                    currentParcialId, 
                    currentCuatriNombre,
                    currentParcialNombre
                });
                
                const parcialElement = document.querySelector(`[data-cuatrimestre-id="${currentCuatriId}"][data-parcial-id="${currentParcialId}"]`);
                if (parcialElement) {
                    parcialElement.classList.add('active');
                    toggleCuatrimestre(currentCuatriId);
                    console.log('✅ Parcial preseleccionado activado');
                }
            @endif
            
            console.log('🚀 Panel de actividades inicializado');
        });

        // Función de test que puedes usar en la consola
        window.testActividades = function() {
            console.log('🧪 Ejecutando test...');
            filtrarActividades(1, 1, 'Test Cuatrimestre', 'Test Parcial');
        };

        console.log('📝 Para probar manualmente, ejecuta: testActividades()');
    </script>
@endsection