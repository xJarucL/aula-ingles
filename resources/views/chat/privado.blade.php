<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1db584, #0ea471);
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #1db584;
        }

        .header-title {
            font-size: 18px;
            font-weight: 500;
            text-decoration: underline;
        }

        .header-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .icon-btn {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .icon-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Navigation tabs */
        .nav-tabs {
            background: white;
            display: flex;
            padding: 0 25px;
            border-bottom: 1px solid #e0e0e0;
        }

        .nav-tab {
            padding: 15px 20px;
            color: #1db584;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-tab.active {
            background: #1db584;
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .nav-tab:hover:not(.active) {
            background: #f0f8f5;
        }

        /* Main container */
        .main-container {
            display: flex;
            min-height: calc(100vh - 120px);
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 0 25px 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .menu-icon {
            margin-right: 10px;
            color: #666;
        }

        .sidebar-title {
            color: #1db584;
            font-size: 18px;
            font-weight: 600;
        }

        /* Chat container */
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
            margin: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .chat-header {
            background: #1db584;
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-title {
            font-size: 20px;
            font-weight: 500;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Messages area */
        .messages-container {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
            max-height: 400px;
            background: #fafafa;
        }

        .message {
            margin-bottom: 15px;
            padding: 12px 16px;
            border-radius: 10px;
            background: white;
            border-left: 4px solid #1db584;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .message-sender {
            font-weight: 600;
            color: #1db584;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .message-text {
            color: #333;
            line-height: 1.4;
        }

        /* Message form */
        .message-form {
            padding: 25px;
            background: white;
            border-top: 1px solid #e0e0e0;
        }

        .form-group {
            display: flex;
            gap: 12px;
        }

        .message-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .message-input:focus {
            border-color: #1db584;
        }

        .send-btn {
            background: #1db584;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .send-btn:hover {
            background: #168a6b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
            }

            .chat-container {
                margin: 10px;
            }

            .header-title {
                display: none;
            }
        }
    </style>
</head>
<body>
    

    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <span class="menu-icon">☰</span>
                <span class="sidebar-title">Mensajes</span>
            </div>
        </div>

        <!-- Chat container -->
        <div class="chat-container">
            <div class="chat-header">
                <h2 class="chat-title">Chat con {{ $user->name }}</h2>
                <button class="back-btn" onclick="history.back()">← Volver</button>
            </div>

            <!-- Messages -->
            <div class="messages-container">
                @foreach($mensajes as $msj)
                <div class="message">
                    <div class="message-sender">{{ $msj->emisor->name }}</div>
                    <div class="message-text">{{ $msj->mensaje }}</div>
                </div>
                @endforeach
            </div>

            <!-- Message form -->
            <div class="message-form">
                <form id="messageForm" action="{{ route('chat.privado.enviar', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input 
                            id="messageInput"
                            name="mensaje" 
                            class="message-input" 
                            placeholder="Escribe tu mensaje..." 
                            required
                        >
                        <button type="submit" class="send-btn">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevenir el envío normal del formulario
            
            const form = this;
            const messageInput = document.getElementById('messageInput');
            const messagesContainer = document.querySelector('.messages-container');
            
            // Obtener el mensaje
            const mensaje = messageInput.value.trim();
            if (!mensaje) return;
            
            // Crear FormData
            const formData = new FormData(form);
            
            // Enviar con fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Crear el nuevo mensaje en el DOM
                    const newMessage = document.createElement('div');
                    newMessage.className = 'message';
                    newMessage.innerHTML = `
                        <div class="message-sender">${data.mensaje.usuario}</div>
                        <div class="message-text">${data.mensaje.mensaje}</div>
                    `;
                    
                    // Añadir el mensaje al contenedor
                    messagesContainer.appendChild(newMessage);
                    
                    // Scroll hasta abajo
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    
                    // Limpiar el input
                    messageInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar el mensaje');
            });
        });

        // Auto-scroll al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.querySelector('.messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
    </script>
</body>
</html>