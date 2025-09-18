<div class="completar-container">
    <div class="completar-header">
        <h3>✏️ Completar Frases</h3>
        <p>Completa cada oración con la palabra correcta</p>
        <div class="completar-progress">
            <span id="ejercicio-completados">0</span> de <span id="total-ejercicios">{{ count($ejercicios) }}</span> completados
        </div>
    </div>

    <form id="completar-form" class="completar-form">
        <div class="ejercicios-lista">
            @foreach($ejercicios as $index => $ejercicio)
                <div class="ejercicio-card" data-ejercicio="{{ $index }}">
                    <div class="ejercicio-numero">
                        Ejercicio {{ $index + 1 }}
                    </div>
                    
                    <div class="ejercicio-contenido">
                        <div class="oracion-container">
                            @php
                                $oracion = $ejercicio['oracion'] ?? 'Oración no disponible';
                                $partes = explode('___', $oracion);
                            @endphp
                            
                            @if(count($partes) >= 2)
                                <div class="oracion-interactiva">
                                    <span class="parte-oracion">{{ $partes[0] }}</span>
                                    <input 
                                        type="text" 
                                        name="completar_{{ $index }}" 
                                        class="input-completar"
                                        placeholder="..."
                                        autocomplete="off"
                                        oninput="verificarComplecion({{ $index }})"
                                        onkeypress="manejarEnter(event, {{ $index }})"
                                    >
                                    <span class="parte-oracion">{{ implode('___', array_slice($partes, 1)) }}</span>
                                </div>
                            @else
                                <div class="oracion-completa">
                                    <p class="oracion-texto">{{ $oracion }}</p>
                                    <input 
                                        type="text" 
                                        name="completar_{{ $index }}" 
                                        class="input-completar-simple"
                                        placeholder="Escribe tu respuesta aquí..."
                                        autocomplete="off"
                                        oninput="verificarComplecion({{ $index }})"
                                        onkeypress="manejarEnter(event, {{ $index }})"
                                    >
                                </div>
                            @endif
                        </div>
                        
                        <div class="ejercicio-estado">
                            <span class="estado-icono" id="estado-{{ $index }}">⏳</span>
                            <span class="estado-texto" id="estado-texto-{{ $index }}">Pendiente</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="completar-acciones">
            <div class="progreso-barra">
                <div class="progreso-fill" id="progreso-fill" style="width: 0%"></div>
            </div>
            
            <button type="button" onclick="finalizarCompletarFrases()" class="btn-finalizar-completar" disabled>
                🏁 Finalizar Ejercicios
            </button>
        </div>
    </form>
</div>

<style>
.completar-container {
    max-width: 100%;
}

.completar-header {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e9ecef;
}

.completar-header h3 {
    margin: 0 0 8px 0;
    color: #333;
    font-size: 1.5rem;
}

.completar-header p {
    color: #666;
    margin: 0 0 12px 0;
}

.completar-progress {
    background: #fff3cd;
    color: #856404;
    padding: 8px 16px;
    border-radius: 20px;
    display: inline-block;
    font-weight: 600;
    border: 1px solid #ffeaa7;
}

.ejercicios-lista {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 24px;
}

.ejercicio-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.ejercicio-card.completado {
    border-color: #28a745;
    background: #d4edda;
}

.ejercicio-numero {
    background: #ff9800;
    color: white;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 16px;
}

.ejercicio-contenido {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.oracion-container {
    flex: 1;
}

.oracion-interactiva {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 1.2rem;
    line-height: 1.5;
}

.parte-oracion {
    color: #333;
    font-weight: 500;
}

.input-completar {
    border: 2px solid #ced4da;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
    min-width: 120px;
    background: white;
    transition: all 0.2s ease;
}

.input-completar:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
}

.input-completar.correcto {
    border-color: #28a745;
    background: #d4edda;
}

.oracion-completa {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.oracion-texto {
    font-size: 1.2rem;
    color: #333;
    font-weight: 500;
    margin: 0;
    padding: 12px;
    background: white;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.input-completar-simple {
    border: 2px solid #ced4da;
    border-radius: 6px;
    padding: 12px 16px;
    font-size: 1.1rem;
    background: white;
    transition: all 0.2s ease;
}

.input-completar-simple:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
}

.ejercicio-estado {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    padding: 8px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.estado-icono {
    font-size: 1.2rem;
}

.estado-texto {
    font-weight: 600;
    font-size: 0.875rem;
}

.completar-acciones {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 2px solid #e9ecef;
    text-align: center;
}

.progreso-barra {
    width: 100%;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    margin-bottom: 16px;
    overflow: hidden;
}

.progreso-fill {
    height: 100%;
    background: linear-gradient(90deg, #ff9800 0%, #ff5722 100%);
    transition: width 0.3s ease;
    border-radius: 4px;
}

.btn-finalizar-completar {
    background: #ff9800;
    color: white;
    border: none;
    padding: 12px 32px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-finalizar-completar:hover:not(:disabled) {
    background: #f57c00;
    transform: translateY(-1px);
}

.btn-finalizar-completar:disabled {
    background: #ced4da;
    color: #6c757d;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 768px) {
    .ejercicio-card {
        padding: 16px;
    }
    
    .oracion-interactiva {
        font-size: 1.1rem;
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    
    .input-completar {
        min-width: 100%;
        margin: 8px 0;
    }
    
    .oracion-texto {
        font-size: 1.1rem;
    }
}
</style>

<script>
let ejerciciosCompletados = 0;
const totalEjercicios = {{ count($ejercicios) }};
let respuestasCompletado = {};

function verificarComplecion(ejercicioIndex) {
    const input = document.querySelector(`input[name="completar_${ejercicioIndex}"]`);
    const estadoIcono = document.getElementById(`estado-${ejercicioIndex}`);
    const estadoTexto = document.getElementById(`estado-texto-${ejercicioIndex}`);
    const ejercicioCard = document.querySelector(`[data-ejercicio="${ejercicioIndex}"]`);
    
    const valor = input.value.trim();
    
    if (valor.length > 0) {
        // Marcar como completado
        if (!respuestasCompletado[ejercicioIndex]) {
            respuestasCompletado[ejercicioIndex] = true;
            ejerciciosCompletados++;
        }
        
        estadoIcono.textContent = '✅';
        estadoTexto.textContent = 'Completado';
        ejercicioCard.classList.add('completado');
        input.classList.add('correcto');
    } else {
        // Marcar como pendiente
        if (respuestasCompletado[ejercicioIndex]) {
            respuestasCompletado[ejercicioIndex] = false;
            ejerciciosCompletados--;
        }
        
        estadoIcono.textContent = '⏳';
        estadoTexto.textContent = 'Pendiente';
        ejercicioCard.classList.remove('completado');
        input.classList.remove('correcto');
    }
    
    actualizarProgreso();
}

function actualizarProgreso() {
    // Actualizar contador
    document.getElementById('ejercicio-completados').textContent = ejerciciosCompletados;
    
    // Actualizar barra de progreso
    const porcentaje = (ejerciciosCompletados / totalEjercicios) * 100;
    document.getElementById('progreso-fill').style.width = `${porcentaje}%`;
    
    // Habilitar/deshabilitar botón finalizar
    const btnFinalizar = document.querySelector('.btn-finalizar-completar');
    btnFinalizar.disabled = ejerciciosCompletados !== totalEjercicios;
}

function manejarEnter(event, ejercicioIndex) {
    if (event.key === 'Enter') {
        event.preventDefault();
        
        // Enfocar siguiente input si existe
        const siguienteInput = document.querySelector(`input[name="completar_${ejercicioIndex + 1}"]`);
        if (siguienteInput) {
            siguienteInput.focus();
        } else if (ejerciciosCompletados === totalEjercicios) {
            finalizarCompletarFrases();
        }
    }
}

function finalizarCompletarFrases() {
    // Verificar que todos los ejercicios estén completados
    if (ejerciciosCompletados !== totalEjercicios) {
        alert(`Por favor completa todos los ejercicios. Te faltan ${totalEjercicios - ejerciciosCompletados}.`);
        return;
    }
    
    // Confirmar finalización
    if (confirm('¿Estás seguro de que quieres finalizar los ejercicios? No podrás cambiar tus respuestas después.')) {
        completarActividad();
    }
}

// Manejar navegación con teclas
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.ctrlKey) {
        if (ejerciciosCompletados === totalEjercicios) {
            finalizarCompletarFrases();
        }
    }
});

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    actualizarProgreso();
    
    // Enfocar primer input
    const primerInput = document.querySelector('input[name="completar_0"]');
    if (primerInput) {
        primerInput.focus();
    }
});
</script>