<div class="quiz-container">
    <div class="quiz-header">
        <h3>❓ Quiz Interactivo</h3>
        <p>Selecciona la respuesta correcta para cada pregunta</p>
        <div class="quiz-progress">
            <span id="pregunta-actual">1</span> de <span id="total-preguntas">{{ count($preguntas) }}</span>
        </div>
    </div>

    <form id="quiz-form" class="quiz-form">
        @foreach($preguntas as $index => $pregunta)
            <div class="pregunta-card {{ $index === 0 ? 'active' : 'hidden' }}" data-pregunta="{{ $index }}">
                <div class="pregunta-numero">
                    Pregunta {{ $index + 1 }}
                </div>
                
                <div class="pregunta-texto">
                    {{ $pregunta['texto'] ?? 'Pregunta sin texto' }}
                </div>
                
                <div class="opciones-container">
                    @if(isset($pregunta['opciones']) && is_array($pregunta['opciones']))
                        @foreach($pregunta['opciones'] as $letra => $opcion)
                            <label class="opcion-item">
                                <input 
                                    type="radio" 
                                    name="respuesta_{{ $index }}" 
                                    value="{{ $letra }}"
                                    onchange="seleccionarOpcion({{ $index }}, '{{ $letra }}')"
                                >
                                <span class="opcion-letra">{{ $letra }}</span>
                                <span class="opcion-texto">{{ $opcion }}</span>
                                <span class="check-mark"></span>
                            </label>
                        @endforeach
                    @else
                        <p class="error-opciones">❌ Esta pregunta no tiene opciones configuradas correctamente.</p>
                    @endif
                </div>
                
                <div class="pregunta-navegacion">
                    @if($index > 0)
                        <button type="button" onclick="anteriorPregunta()" class="btn-anterior">
                            ← Anterior
                        </button>
                    @endif
                    
                    @if($index < count($preguntas) - 1)
                        <button type="button" onclick="siguientePregunta()" class="btn-siguiente" disabled>
                            Siguiente →
                        </button>
                    @else
                        <button type="button" onclick="finalizarQuiz()" class="btn-finalizar" disabled>
                            🏁 Finalizar Quiz
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </form>
</div>

<style>
.quiz-container {
    max-width: 100%;
}

.quiz-header {
    text-align: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e9ecef;
}

.quiz-header h3 {
    margin: 0 0 8px 0;
    color: #333;
    font-size: 1.5rem;
}

.quiz-header p {
    color: #666;
    margin: 0 0 12px 0;
}

.quiz-progress {
    background: #f8f9fa;
    padding: 8px 16px;
    border-radius: 20px;
    display: inline-block;
    font-weight: 600;
    color: #495057;
}

.pregunta-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.pregunta-card.active {
    border-color: #1E847D;
    box-shadow: 0 4px 12px rgba(30, 132, 125, 0.1);
}

.pregunta-card.hidden {
    display: none;
}

.pregunta-numero {
    background: #1E847D;
    color: white;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 16px;
}

.pregunta-texto {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 20px;
    line-height: 1.5;
    font-weight: 500;
}

.opciones-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 24px;
}

.opcion-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.opcion-item:hover {
    border-color: #1E847D;
    background: #f8fff8;
}

.opcion-item input[type="radio"] {
    display: none;
}

.opcion-item input[type="radio"]:checked + .opcion-letra {
    background: #1E847D;
    color: white;
}

.opcion-item input[type="radio"]:checked ~ .check-mark {
    opacity: 1;
}

.opcion-letra {
    background: #e9ecef;
    color: #495057;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.opcion-texto {
    flex: 1;
    color: #333;
    font-size: 1rem;
    line-height: 1.4;
}

.check-mark {
    position: absolute;
    right: 16px;
    opacity: 0;
    color: #1E847D;
    font-size: 1.2rem;
    transition: opacity 0.2s ease;
}

.check-mark::before {
    content: "✓";
}

.pregunta-navegacion {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.btn-anterior, .btn-siguiente, .btn-finalizar {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 1rem;
}

.btn-anterior {
    background: #6c757d;
    color: white;
}

.btn-anterior:hover {
    background: #5a6268;
}

.btn-siguiente, .btn-finalizar {
    background: #1E847D;
    color: white;
    margin-left: auto;
}

.btn-siguiente:hover, .btn-finalizar:hover {
    background: #16635c;
}

.btn-siguiente:disabled, .btn-finalizar:disabled {
    background: #ced4da;
    color: #6c757d;
    cursor: not-allowed;
}

.error-opciones {
    text-align: center;
    color: #dc3545;
    font-style: italic;
    padding: 20px;
    background: #f8d7da;
    border-radius: 8px;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .pregunta-card {
        padding: 16px;
    }
    
    .pregunta-texto {
        font-size: 1.1rem;
    }
    
    .opcion-item {
        padding: 12px;
    }
    
    .pregunta-navegacion {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-anterior, .btn-siguiente, .btn-finalizar {
        width: 100%;
        margin-left: 0;
    }
}
</style>

<script>
let preguntaActual = 0;
const totalPreguntas = {{ count($preguntas) }};
let respuestas = {};

function seleccionarOpcion(preguntaIndex, opcion) {
    respuestas[preguntaIndex] = opcion;
    
    // Habilitar botón siguiente/finalizar
    const preguntaCard = document.querySelector(`[data-pregunta="${preguntaIndex}"]`);
    const btnSiguiente = preguntaCard.querySelector('.btn-siguiente');
    const btnFinalizar = preguntaCard.querySelector('.btn-finalizar');
    
    if (btnSiguiente) {
        btnSiguiente.disabled = false;
    }
    if (btnFinalizar) {
        btnFinalizar.disabled = false;
    }
}

function siguientePregunta() {
    if (preguntaActual < totalPreguntas - 1) {
        // Ocultar pregunta actual
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.add('hidden');
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.remove('active');
        
        // Mostrar siguiente pregunta
        preguntaActual++;
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.remove('hidden');
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.add('active');
        
        // Actualizar progreso
        document.getElementById('pregunta-actual').textContent = preguntaActual + 1;
        
        // Scroll al inicio
        document.querySelector('.pregunta-card.active').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
}

function anteriorPregunta() {
    if (preguntaActual > 0) {
        // Ocultar pregunta actual
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.add('hidden');
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.remove('active');
        
        // Mostrar pregunta anterior
        preguntaActual--;
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.remove('hidden');
        document.querySelector(`[data-pregunta="${preguntaActual}"]`).classList.add('active');
        
        // Actualizar progreso
        document.getElementById('pregunta-actual').textContent = preguntaActual + 1;
        
        // Scroll al inicio
        document.querySelector('.pregunta-card.active').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
}

function finalizarQuiz() {
    // Verificar que todas las preguntas estén contestadas
    const preguntasSinResponder = [];
    for (let i = 0; i < totalPreguntas; i++) {
        if (!respuestas.hasOwnProperty(i)) {
            preguntasSinResponder.push(i + 1);
        }
    }
    
    if (preguntasSinResponder.length > 0) {
        alert(`Por favor responde las preguntas: ${preguntasSinResponder.join(', ')}`);
        return;
    }
    
    // Confirmar finalización
    if (confirm('¿Estás seguro de que quieres finalizar el quiz? No podrás cambiar tus respuestas después.')) {
        completarActividad();
    }
}

// Manejar teclas de navegación
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowRight' || e.key === 'Enter') {
        if (preguntaActual < totalPreguntas - 1 && respuestas.hasOwnProperty(preguntaActual)) {
            siguientePregunta();
        } else if (preguntaActual === totalPreguntas - 1 && respuestas.hasOwnProperty(preguntaActual)) {
            finalizarQuiz();
        }
    } else if (e.key === 'ArrowLeft') {
        if (preguntaActual > 0) {
            anteriorPregunta();
        }
    }
});

// Inicializar estado de botones
document.addEventListener('DOMContentLoaded', function() {
    // Si hay respuestas previas guardadas, restaurar estado
    document.querySelectorAll('input[type="radio"]').forEach(input => {
        const preguntaIndex = parseInt(input.name.replace('respuesta_', ''));
        if (respuestas[preguntaIndex] === input.value) {
            input.checked = true;
            seleccionarOpcion(preguntaIndex, input.value);
        }
    });
});
</script>