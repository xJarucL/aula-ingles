@forelse($actividades as $actividad)
    <div class="actividad-card"
         data-cuatrimestre="{{ $actividad->parcial->cuatrimestre->id }}"
         data-parcial="{{ $actividad->parcial->id }}">
        <div class="actividad-imagen">
            @if($actividad->imagen)
                <img src="{{ asset('storage/' . $actividad->imagen) }}" alt="{{ $actividad->nombre }}">
            @else
                <div class="imagen-placeholder">📚</div>
            @endif
        </div>
        <div class="actividad-info">
            <h3>{{ $actividad->nombre }}</h3>
            <div class="actividad-meta">
                <span>{{ $actividad->parcial->cuatrimestre->nombre }} - {{ $actividad->parcial->nombre }}</span>
                @if($actividad->descripcion)
                    <small>{{ Str::limit($actividad->descripcion, 60) }}</small>
                @endif
            </div>
        </div>
        <div class="actividad-acciones">
            <button onclick="verActividad({{ $actividad->id }})" class="btn-accion btn-ver" title="Ver actividad">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
            <button onclick="editarActividad({{ $actividad->id }})" class="btn-accion btn-editar" title="Editar actividad">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </button>
            <button onclick="eliminarActividad({{ $actividad->id }})" class="btn-accion btn-eliminar" title="Eliminar actividad">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3,6 5,6 21,6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2 2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </button>
        </div>
    </div>
@empty
    <div class="sin-actividades">
        <div class="sin-actividades-icon">📚</div>
        <h2>No hay actividades para este parcial</h2>
        <p>Crea una nueva actividad para este cuatrimestre y parcial</p>
        <!-- RUTA CORREGIDA -->
        <button class="btn-crear-primera" onclick="window.location.href='{{ route('profesores.actividades.crear') }}'">
            Crear Actividad
        </button>
    </div>
@endforelse