@php
    // Obtener ejercicios desde diferentes ubicaciones posibles
    $ejercicios = [];
    if (isset($contenido['contenido']['ejercicios'])) {
        $ejercicios = $contenido['contenido']['ejercicios'];
    } elseif (isset($contenido['ejercicios'])) {
        $ejercicios = $contenido['ejercicios'];
    }
@endphp

<div class="completar-container">
    @if(empty($ejercicios))
        <div style="text-align: center; padding: 40px;">
            <h3 style="color: #dc3545;">No se encontraron ejercicios</h3>
            <p style="color: #666;">Esta actividad no tiene ejercicios configurados correctamente.</p>
        </div>
    @else
        <!-- Instrucciones -->
        <div style="background: #e8f5e8; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
            <h4 style="color: #28a745; margin-bottom: 10px;">✏️ Instrucciones:</h4>
            <ul style="color: #666; margin: 0; padding-left: 20px;">
                <li>Completa cada espacio en blanco con la palabra correcta</li>
                <li>Escribe tu respuesta en el campo de texto</li>
                <li>Revisa tu ortografía antes de enviar</li>
                <li>Algunas respuestas pueden tener variaciones aceptables</li>
            </ul>
        </div>

        <!-- Ejercicios -->
        <div class="ejercicios-grid">
            @foreach($ejercicios as $index => $ejercicio)
                <div class="ejercicio-card" data-ejercicio="{{ $index }}">
                    <div class="ejercicio-numero">{{ $index + 1 }}</div>
                    
                    <div class="ejercicio-contenido">
                        @if(isset($ejercicio['pregunta']))
                            <div class="ejercicio-pregunta">
                                {{ $ejercicio['pregunta'] }}
                            </div>
                        @endif

                        @if(isset($ejercicio['oracion']))
                            <div class="ejercicio-oracion">
                                @php
                                    $oracionConInput = str_replace(
                                        '___', 
                                        '<input type="text" class="completar-input" name="completar_' . $index . '" data-ejercicio="' . $index . '" placeholder="...">', 
                                        $ejercicio['oracion']
                                    );
                                @endphp
                                {!! $oracionConInput !!}
                            </div>
                        @elseif(isset($ejercicio['texto']))
                            <div class="ejercicio-texto">
                                {{ $ejercicio['texto'] }}
                            </div>
                            <div class="ejercicio-input">
                                <input type="text" 
                                       class="completar-input" 
                                       name="completar_{{ $index }}" 
                                       data-ejercicio="{{ $index }}" 
                                       placeholder="Escribe tu respuesta aquí...">
                            </div>
                        @endif

                        @if(isset($ejercicio['pista']))
                            <div class="ejercicio-pista">
                                💡 <strong>Pista:</strong> {{ $ejercicio['pista'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botones de navegación -->
        <div class="completar-navegacion">
            <div class="progreso-info">
                <div class="progreso-texto">
                    Ejercicios completados: <span id="ejercicios-completados">0</span> de {{ count($ejercicios) }}
                </div>
                <div class="progreso-bar">
                    <div class="progreso-fill" style="width: 0%"></div>
                </div>
            </div>

            <button type="button" 
                    id="btnFinalizarCompletar" 
                    class="btn-navegacion btn-finalizar" 
                    onclick="finalizarActividad()">
                ✅ Finalizar Ejercicios
            </button>
        </div>
    @endif
</div>

<style>
    .completar-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .ejercicios-grid {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .ejercicio-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        border-left: 4px solid #28a745;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .ejercicio-numero {
        background: #28a745;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 15px;
        float: left;
        margin-right: 15px;
    }

    .ejercicio-contenido {
        overflow: hidden;
    }

    .ejercicio-pregunta {
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .ejercicio-oracion {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
        margin-bottom: 15px;
    }

    .ejercicio-texto {
        font-size: 1rem;
        color: #555;
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .completar-input {
        border: 2px solid #28a745;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 1rem;
        min-width: 120px;
        margin: 0 5px;
        transition: all 0.3s ease;
    }

    .completar-input:focus {
        outline: none;
        border-color: #20c997;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }

    .ejercicio-input .completar-input {
        width: 100%;
        margin: 0;
    }

    .ejercicio-pista {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 6px;
        padding: 10px;
        margin-top: 15px;
        font-size: 0.9rem;
        color: #856404;
    }

    .completar-navegacion {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 25px;
        margin-top: 30px;
        text-align: center;
    }

    .progreso-info {
        margin-bottom: 20px;
    }

    .progreso-texto {
        color: #666;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .progreso-bar {
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
        max-width: 400px;
        margin: 0 auto;
    }

    .progreso-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    @media (max-width: 768px) {
        .ejercicio-card {
            padding: 20px;
        }

        .ejercicio-numero {
            float: none;
            margin: 0 auto 15px;
        }

        .completar-input {
            min-width: 100px;
            margin: 5px 0;
        }
    }
</style>

<script>
// Inicializar actividad de completar
function inicializarCompletar() {
    console.log('✏️ Inicializando actividad de completar...');
    
    const inputs = document.querySelectorAll('.completar-input');
    totalPreguntas = inputs.length;
    
    // Configurar eventos para cada input
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            const ejercicioIndex = this.dataset.ejercicio;
            const valor = this.value.trim();
            
            if (valor) {
                respuestas[ejercicioIndex] = valor;
            } else {
                delete respuestas[ejercicioIndex];
            }
            
            actualizarProgresoCompletar();
        });

        // Autoguardar al cambiar de campo
        input.addEventListener('blur', function() {
            if (typeof autoguardarRespuestas === 'function') {
                autoguardarRespuestas();
            }
        });

        // Enter para ir al siguiente campo
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const siguienteInput = this.parentElement.parentElement.nextElementSibling?.querySelector('.completar-input');
                if (siguienteInput) {
                    siguienteInput.focus();
                } else {
                    // Si es el último, enfocar botón finalizar
                    document.getElementById('btnFinalizarCompletar')?.focus();
                }
            }
        });
    });

    actualizarProgresoCompletar();
}

function actualizarProgresoCompletar() {
    const completados = Object.keys(respuestas).length;
    const porcentaje = totalPreguntas > 0 ? (completados / totalPreguntas) * 100 : 0;
    
    // Actualizar texto
    const textoElement = document.getElementById('ejercicios-completados');
    if (textoElement) {
        textoElement.textContent = completados;
    }
    
    // Actualizar barra de progreso
    const barraElement = document.querySelector('.progreso-fill');
    if (barraElement) {
        barraElement.style.width = porcentaje + '%';
    }

    // Habilitar/deshabilitar botón finalizar
    const btnFinalizar = document.getElementById('btnFinalizarCompletar');
    if (btnFinalizar) {
        if (completados === totalPreguntas) {
            btnFinalizar.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            btnFinalizar.disabled = false;
        } else {
            btnFinalizar.style.background = '#6c757d';
            btnFinalizar.disabled = false; // Permitir finalizar parcialmente
        }
    }
}

// Validación específica para ejercicios de completar
function validarCompletarCompleto() {
    const ejerciciosSinCompletar = [];
    
    for (let i = 0; i < totalPreguntas; i++) {
        if (!respuestas.hasOwnProperty(i) || !respuestas[i].trim()) {
            ejerciciosSinCompletar.push(i + 1);
        }
    }
    
    if (ejerciciosSinCompletar.length > 0) {
        const mensaje = `⚠️ Te faltan ${ejerciciosSinCompletar.length} ejercicio(s) por completar:\n\n` +
                       `Ejercicios: ${ejerciciosSinCompletar.join(', ')}\n\n` +
                       `¿Quieres enviar las respuestas que has completado?`;
        
        return confirm(mensaje);
    }
    
    return true;
}

// Función específica para finalizar completar
function finalizarActividadCompletar() {
    if (!validarCompletarCompleto()) {
        return;
    }
    
    // Confirmar envío
    const completados = Object.keys(respuestas).length;
    const confirmacion = confirm(
        `✏️ ¿Estás seguro de que quieres enviar los ejercicios?\n\n` +
        `✅ Ejercicios completados: ${completados}/${totalPreguntas}\n` +
        `⏱️ Tiempo transcurrido: ${Math.round((Date.now() - tiempoInicio) / 1000)} segundos\n\n` +
        `⚠️ No podrás cambiar tus respuestas después de enviar.`
    );
    
    if (confirmacion) {
        if (typeof completarActividad === 'function') {
            completarActividad();
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en una actividad de completar
    if (document.querySelector('.completar-container')) {
        inicializarCompletar();
        
        // Sobrescribir la función global de finalizar si existe
        if (typeof window.finalizarActividad !== 'undefined') {
            window.finalizarActividadOriginal = window.finalizarActividad;
            window.finalizarActividad = finalizarActividadCompletar;
        }
    }
});
</script>