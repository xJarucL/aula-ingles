<script>
// === VARIABLES GLOBALES ===
let modoActual = 'crear';
let preguntasImportadas = [];
let pdfProcesado = false;

// === INICIALIZACIÓN ===
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Documento cargado');
    configurarFormulario();
});

// === CONFIGURACIÓN SIMPLIFICADA DEL FORMULARIO ===
function configurarFormulario() {
    const form = document.getElementById('formActividad');
    const btnCrear = document.querySelector('button[type="submit"]');
    
    if (!form) {
        console.error('❌ No se encontró el formulario');
        return;
    }

    // Remover listeners previos
    form.removeEventListener('submit', manejadorFormulario);
    
    // Agregar nuevo listener
    form.addEventListener('submit', manejadorFormulario);
    
    console.log('✅ Formulario configurado correctamente');
}

// === MANEJADOR SIMPLIFICADO DEL FORMULARIO ===
function manejadorFormulario(e) {
    console.log('📝 Enviando formulario...');
    
    // Validaciones básicas
    const nombre = document.getElementById('nombre').value.trim();
    const cuatrimestreSelect = document.getElementById('cuatrimestre_id');
    const parcialSelect = document.getElementById('parcial_id');
    
    if (!nombre) {
        e.preventDefault();
        alert('❌ Por favor ingresa el nombre de la actividad');
        return false;
    }
    
    if (!cuatrimestreSelect.value) {
        e.preventDefault();
        alert('❌ Por favor selecciona un cuatrimestre');
        return false;
    }
    
    if (!parcialSelect.value) {
        e.preventDefault();
        alert('❌ Por favor selecciona un parcial');
        return false;
    }

    // Preparar contenido según el modo
    let contenidoInput = document.getElementById('contenido');
    if (!contenidoInput) {
        contenidoInput = document.createElement('input');
        contenidoInput.type = 'hidden';
        contenidoInput.name = 'contenido';
        contenidoInput.id = 'contenido';
        form.appendChild(contenidoInput);
    }

    if (modoActual === 'importar' && preguntasImportadas.length > 0) {
        // Modo PDF
        const contenidoData = {
            tipo: 'quiz',
            contenido: {
                preguntas: preguntasImportadas
            }
        };
        contenidoInput.value = JSON.stringify(contenidoData);
        console.log('📄 Enviando contenido PDF:', preguntasImportadas.length, 'preguntas');
    } else {
        // Modo crear manual
        const contenidoData = {
            tipo: 'manual',
            contenido: {}
        };
        contenidoInput.value = JSON.stringify(contenidoData);
        console.log('✨ Enviando actividad manual');
    }

    console.log('✅ Formulario válido, enviando...');
    return true;
}

// === CAMBIO DE MODO ===
function cambiarModo(modo, boton) {
    console.log('🔄 Cambiando modo a:', modo);
    
    modoActual = modo;
    
    // Actualizar botones
    document.querySelectorAll('.modo-btn').forEach(btn => btn.classList.remove('active'));
    boton.classList.add('active');
    
    // Mostrar/ocultar secciones
    document.querySelectorAll('.modo-contenido').forEach(section => {
        section.classList.remove('active');
    });
    
    const seccionActiva = document.getElementById(`modo-${modo}`);
    if (seccionActiva) {
        seccionActiva.classList.add('active');
    }
}

// === FUNCIONES AUXILIARES ===
function mostrarVistaPreviaCrear() {
    alert('Vista previa disponible después de agregar contenido');
}

function mostrarVistaPreviaPDF() {
    if (preguntasImportadas.length === 0) {
        alert('No hay preguntas importadas del PDF');
        return;
    }
    
    let mensaje = `=== VISTA PREVIA PDF ===\n\n`;
    mensaje += `Total de preguntas: ${preguntasImportadas.length}\n\n`;
    
    preguntasImportadas.slice(0, 3).forEach((p, i) => {
        mensaje += `${i + 1}. ${p.pregunta}\n`;
        mensaje += `   A) ${p.opciones.A}\n`;
        mensaje += `   B) ${p.opciones.B}\n`;
        mensaje += `   C) ${p.opciones.C}\n`;
        mensaje += `   D) ${p.opciones.D}\n`;
        mensaje += `   Correcta: ${p.correcta}\n\n`;
    });
    
    if (preguntasImportadas.length > 3) {
        mensaje += `... y ${preguntasImportadas.length - 3} preguntas más`;
    }
    
    alert(mensaje);
}

// === LOG DE DEBUG ===
console.log('🚀 Script de actividades cargado');
</script>