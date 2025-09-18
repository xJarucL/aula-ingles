{{-- 
    Este archivo va en: resources/views/profesores/actividades/crear-actividad-scripts.blade.php 
    REEMPLAZA TODO el contenido existente con esto:
--}}

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

// === FUNCIÓN PARA CARGAR PARCIALES ===
function cargarParciales() {
    const cuatrimestreId = document.getElementById('cuatrimestre_id').value;
    const parcialSelect = document.getElementById('parcial_id');
    
    // Limpiar opciones anteriores
    parcialSelect.innerHTML = '<option value="">Cargando parciales...</option>';
    
    if (!cuatrimestreId) {
        parcialSelect.innerHTML = '<option value="">Selecciona primero el cuatrimestre...</option>';
        return;
    }

    console.log('🔄 Cargando parciales para cuatrimestre:', cuatrimestreId);

    // Datos de parciales (puedes cambiarlo por una llamada AJAX si necesitas)
    const parciales = {
        '1': [
            { id: 1, nombre: 'Primer Parcial' },
            { id: 2, nombre: 'Segundo Parcial' },
            { id: 3, nombre: 'Tercer Parcial' }
        ],
        '2': [
            { id: 4, nombre: 'Primer Parcial' },
            { id: 5, nombre: 'Segundo Parcial' },
            { id: 6, nombre: 'Tercer Parcial' }
        ],
        '3': [
            { id: 7, nombre: 'Primer Parcial' },
            { id: 8, nombre: 'Segundo Parcial' },
            { id: 9, nombre: 'Tercer Parcial' }
        ]
    };

    // Construir opciones
    let opciones = '<option value="">Selecciona el parcial...</option>';
    
    if (parciales[cuatrimestreId]) {
        parciales[cuatrimestreId].forEach(parcial => {
            opciones += `<option value="${parcial.id}">${parcial.nombre}</option>`;
        });
    }
    
    parcialSelect.innerHTML = opciones;
    console.log('✅ Parciales cargados');
}

// === FUNCIÓN MEJORADA PARA VISTA PREVIA ===
function mostrarVistaPrevia() {
    const nombre = document.getElementById('nombre').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    
    if (!nombre) {
        alert('❌ Primero ingresa el nombre de la actividad');
        return;
    }

    if (modoActual === 'crear') {
        mostrarVistaPreviaCrear();
    } else {
        mostrarVistaPreviaPDF();
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

// === FUNCIÓN PARA PROCESAR PDF ===
function procesarPDF() {
    const archivoInput = document.getElementById('archivo_pdf');
    const archivo = archivoInput.files[0];
    
    if (!archivo) {
        alert('❌ Por favor selecciona un archivo PDF');
        return;
    }

    if (archivo.type !== 'application/pdf') {
        alert('❌ Por favor selecciona un archivo PDF válido');
        return;
    }

    console.log('📄 Procesando PDF:', archivo.name);
    
    // Simular procesamiento (aquí puedes agregar tu lógica de procesamiento real)
    setTimeout(() => {
        // Datos de ejemplo
        preguntasImportadas = [
            {
                pregunta: "¿Cuál es la forma correcta del Present Simple?",
                opciones: {
                    A: "I go to school",
                    B: "I goes to school", 
                    C: "I going to school",
                    D: "I went to school"
                },
                correcta: "A"
            },
            {
                pregunta: "¿Cómo se forma la tercera persona del singular?",
                opciones: {
                    A: "Agregando -ed",
                    B: "Agregando -s o -es", 
                    C: "Sin cambios",
                    D: "Agregando -ing"
                },
                correcta: "B"
            }
        ];
        
        pdfProcesado = true;
        
        document.getElementById('pdf-preview').innerHTML = `
            <div class="pdf-procesado">
                <h4>✅ PDF procesado exitosamente</h4>
                <p>Se encontraron ${preguntasImportadas.length} preguntas</p>
                <button type="button" onclick="mostrarVistaPreviaPDF()" class="btn-ver-preguntas">Ver preguntas</button>
            </div>
        `;
        
        console.log('✅ PDF procesado:', preguntasImportadas.length, 'preguntas');
    }, 2000);
    
    // Mostrar mensaje de procesamiento
    document.getElementById('pdf-preview').innerHTML = `
        <div class="procesando">
            <p>🔄 Procesando PDF...</p>
        </div>
    `;
}

// === LOG DE DEBUG ===
console.log('🚀 Script de actividades cargado');
</script>