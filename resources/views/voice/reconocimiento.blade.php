@extends('layouts.app')

@section('content')
<div class="container" style="margin-top:60px;display:flex;justify-content:center;">
    <div class="voice-card" style="background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(30,132,125,0.08);padding:40px 30px 30px 30px;max-width:500px;width:100%;">
        <h2 style="color:#1E847D;margin-bottom:18px;text-align:center;">Reconocimiento de Voz a Texto (Prueba)</h2>
        
        <form method="post" action="{{ route('voice.update-phrase') }}" style="margin-bottom:18px;text-align:center;">
            @csrf
            <label for="frase_esperada" style="color:#444;font-weight:bold;">Frase esperada (puedes editarla):</label>
            <input type="text" 
                   name="frase_esperada" 
                   id="frase_esperada" 
                   value="{{ $fraseEsperada }}" 
                   style="width:100%;margin-top:8px;padding:10px 15px;border-radius:6px;border:1px solid #e0e0e0;font-size:1.1rem;background:#e0f2e9;color:#1E847D;text-align:center;">
            <button type="submit" class="login-btn" style="margin-top:12px;width:100%;">Actualizar frase</button>
        </form>
        
        <div class="input-group mb-3" style="display:flex;gap:10px;">
            <input type="text" 
                   class="form-control" 
                   id="speechToText" 
                   placeholder="Presiona el micrófono y habla" 
                   style="flex:1;font-size:1.1rem;padding:10px 15px;border:1px solid #e0e0e0;border-radius:6px;"
                   readonly>
            <button class="btn-mic" onclick="record()" type="button" 
                    style="background:#00d6b6;color:#fff;border:none;padding:0 18px;border-radius:8px;font-size:1.3rem;cursor:pointer;transition:all 0.3s;">
                <span class="mic-icon">🎤</span> Hablar
            </button>
        </div>
        
        <div style="margin:18px 0 0 0;">
            <button onclick="verificarFrase()" class="login-btn" style="width:100%;">Verificar coincidencia</button>
        </div>
        
        <div id="verificacionResultado" style="margin-top:18px;font-size:1.1rem;text-align:center;"></div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('inicio') }}" 
               style="color: #1E847D; text-decoration: none; font-weight: 500;">
                ← Volver al inicio
            </a>
        </div>
    </div>
</div>

<script>
function record() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        alert('Tu navegador no soporta reconocimiento de voz. Prueba con Google Chrome.');
        return;
    }
    
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    
    recognition.lang = "es-US"; 
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;
    
    recognition.onstart = function() {
        const btnMic = document.querySelector('.btn-mic');
        const micIcon = document.querySelector('.mic-icon');
        const speechInput = document.getElementById("speechToText");
        
        btnMic.style.background = '#e74c3c';
        micIcon.textContent = '🔴';
        speechInput.placeholder = "Escuchando...";
        speechInput.value = "";
    };
    
    recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        document.getElementById("speechToText").value = transcript;
    };
    
    recognition.onerror = function(event) {
        console.error('Error de reconocimiento:', event.error);
        
        let errorMessage = 'Error en el reconocimiento de voz';
        switch(event.error) {
            case 'no-speech':
                errorMessage = 'No se detectó habla. Intenta de nuevo.';
                break;
            case 'audio-capture':
                errorMessage = 'No se puede acceder al micrófono.';
                break;
            case 'not-allowed':
                errorMessage = 'Permiso de micrófono denegado.';
                break;
            case 'network':
                errorMessage = 'Error de red. Verifica tu conexión.';
                break;
            default:
                errorMessage = 'Error: ' + event.error;
        }
        
        alert(errorMessage);
        resetMicButton();
    };
    
    recognition.onend = function() {
        resetMicButton();
    };
    
    try {
        recognition.start();
    } catch (error) {
        console.error('Error al iniciar reconocimiento:', error);
        alert('No se pudo iniciar el reconocimiento de voz');
        resetMicButton();
    }
}

function resetMicButton() {
    const btnMic = document.querySelector('.btn-mic');
    const micIcon = document.querySelector('.mic-icon');
    const speechInput = document.getElementById("speechToText");
    
    btnMic.style.background = '#00d6b6';
    micIcon.textContent = '🎤';
    speechInput.placeholder = "Presiona el micrófono y habla";
}

async function verificarFrase() {
    const esperado = document.getElementById("frase_esperada").value;
    const dicho = document.getElementById("speechToText").value;
    const resultado = document.getElementById("verificacionResultado");
    
    if (!dicho.trim()) {
        resultado.innerHTML = "<span style='color:#c0392b;'>Por favor, di la frase y presiona verificar.</span>";
        return;
    }
    
    resultado.innerHTML = "<span style='color:#666;'>Verificando...</span>";
    
    try {
        const response = await fetch('{{ route("voice.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                frase_esperada: esperado,
                frase_dicha: dicho
            })
        });
        
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        
        const data = await response.json();
        
        let colorClass = '';
        let icon = '';
        
        switch(data.status) {
            case 'correcto':
                colorClass = '#00b894';
                icon = '✅ ';
                break;
            case 'parcial':
                colorClass = '#f39c12';
                icon = '⚠️ ';
                break;
            case 'incorrecto':
                colorClass = '#c0392b';
                icon = '❌ ';
                break;
        }
        
        resultado.innerHTML = `<span style='color:${colorClass};font-weight:bold;'>${icon}${data.message}</span>`;
        
        if (data.status === 'correcto') {
            setTimeout(() => {
                document.getElementById("speechToText").value = '';
                resultado.innerHTML = '';
            }, 3000);
        }
        
    } catch (error) {
        resultado.innerHTML = "<span style='color:#c0392b;'>❌ Error al verificar. Intenta de nuevo.</span>";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        const btnMic = document.querySelector('.btn-mic');
        btnMic.style.background = '#ccc';
        btnMic.style.cursor = 'not-allowed';
        btnMic.innerHTML = '<span class="mic-icon">🚫</span> No soportado';
        btnMic.onclick = function() {
            alert('Tu navegador no soporta reconocimiento de voz. Por favor, usa Google Chrome o Edge.');
        };
    }
});
</script>
@endsection