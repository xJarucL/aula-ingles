<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir tarea</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #acb6e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .upload-box {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
            position: relative;
        }

        h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #2c3e50;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: bold;
            color: #34495e;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
        }

        button:hover {
            background-color: #1f6391;
            transform: scale(1.05);
        }

        .alert {
            position: absolute;
            top: -30px;
            left: 0;
            right: 0;
            margin: auto;
            background-color: #27ae60;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            animation: slideDown 0.5s ease forwards;
        }

        .alert.error {
            background-color: #c0392b;
        }

        @keyframes fadeIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 500px) {
            .upload-box {
                margin: 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
    <div class="upload-box">
        @if (session('success'))
            <div class="alert">✅ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert error">❌ {{ session('error') }}</div>
        @endif

        <h2>Subir tarea</h2>

        <form action="{{ route('tareas.subir') }}" method="POST" enctype="multipart/form-data" onsubmit="mostrarCargando()">
            @csrf

            <div>
                <label for="nombre">Nombre completo del alumno</label>
                <input type="text" id="nombre" name="alumno" required>
            </div>

            <div>
                <label for="archivo">Archivo (.pdf, .docx, .doc)</label>
                <input type="file" id="archivo" name="archivo" accept=".pdf,.doc,.docx" required>
            </div>

            <button id="submitBtn" type="submit">Subir</button>
        </form>
    </div>

    <script>
        function mostrarCargando() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = "Subiendo...";
            btn.disabled = true;
            btn.style.backgroundColor = "#7f8c8d";
            btn.style.cursor = "not-allowed";
        }

        window.onload = function () {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 300);
                }, 3000);
            }
        }
    </script>

</body>
</html>
