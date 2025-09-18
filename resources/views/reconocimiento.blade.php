<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reconocimiento de Voz a Texto</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <style>
        body {
            margin: 0;
            background-color: #ddd;
            font-family: sans-serif;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .voice-card {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 2px 4px 12px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #062246;
        }

        input[type="text"] {
            font-size: 18px;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .btn-mic {
            margin-top: 20px;
            background-color: #26d3b8;
            color: white;
            font-weight: bold;
            border: none;
            font-size: 18px;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-mic:hover {
            background-color: #1ba291;
        }

        .mic-icon {
            font-size: 20px;
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="voice-card">
        <h2>Reconocimiento de Voz a Texto</h2>

        <input type="text" id="speechToText" placeholder="Presiona el micrófono y habla">

        <button class="btn-mic" onclick="record()">
            <span class="mic-icon">🎤</span> Hablar
        </button>
    </div>
</div>

<script>
function record() {
    var recognition = new webkitSpeechRecognition();
    recognition.lang = "es-ES";
    recognition.onresult = function(event) {
        document.getElementById('speechToText').value = event.results[0][0].transcript;
    }
    recognition.start();
}
</script>

</body>
</html>
