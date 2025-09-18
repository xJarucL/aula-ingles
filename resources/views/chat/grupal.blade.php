<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Grupal</title>
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

        /* Online users */
        .online-users {
            padding: 15px 25px;
        }

        .online-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .online-count {
            background: #1db584;
            color: white;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            gap: 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #1db584, #0ea471);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .user-name {
            font-size: 14px;
            color: #333;
        }

        .online-dot {
            width: 8px;
            height: 8px;
            background: #4CAF50;
            border-radius: 50%;
            margin-left: auto;
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
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-icon {
            font-size: 24px;
        }

        .chat-info {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
        }

        .participants-count {
            background: rgba(255,255,255,0.2);
            padding: 4px 10px;
            border-radius: 12px;
        }

        /* Success message */
        .success-message {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #1db584;
            transition: transform 0.2s ease;
        }

        .message:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .message-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 8px;
        }

        .message-sender {
            font-weight: 600;
            color: #1db584;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sender-avatar {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #1db584, #0ea471);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }

        .message-time {
            color: #666;
            font-size: 12px;
            margin-left: auto;
        }

        .message-text {
            color: #333;
            line-height: 1.5;
            margin: 10px 0;
        }

        /* Message form */
        .message-form {
            padding: 25px;
            background: white;
            border-top: 1px solid #e0e0e0;
        }

        .form-container {
            display: flex;
            gap: 12px;
            align-items: end;
        }

        .message-textarea {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            resize: vertical;
            min-height: 45px;
            max-height: 120px;
            transition: border-color 0.3s;
        }

        .message-textarea:focus {
            border-color: #1db584;
        }

        .send-btn {
            background: #1db584;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 45px;
        }

        .send-btn:hover {
            background: #168a6b;
            transform: translateY(-1px);
        }

        .send-btn:active {
            transform: translateY(0);
        }

        /* Empty state */
        .empty-messages {
            text-align: center;
            padding: 60px 25px;
            color: #666;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .empty-text {
            font-size: 14px;
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

            .form-container {
                flex-direction: column;
                gap: 10px;
            }

            .send-btn {
                width: 100%;
                justify-content: center;
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
        }
    </style>
</head>
<body>
    

    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <span class="menu-icon">☰</span>
                <span class="sidebar-title">Chat Grupal</span>
                <a class="back-btn" onclick="history.back()">← Volver</a>
            </div>

            <div class="online-users">
                <div class="online-title">
                    🟢 Usuarios conectados
                    <span class="online-count" id="onlineCount">0</span>
                </div>
                
                <!-- Simulación de usuarios conectados -->
                <div class="user-item">
                    <div class="user-avatar">U</div>
                    <div class="user-name">Usuario 1</div>
                    <div class="online-dot"></div>
                </div>
                <div class="user-item">
                    <div class="user-avatar">U</div>
                    <div class="user-name">Usuario 2</div>
                    <div class="online-dot"></div>
                </div>
            </div>
        </div>

        <!-- Chat container -->
        <div class="chat-container">
            <div class="chat-header">
                <div class="chat-title">
                    <span class="chat-icon">💬</span>
                    Chat Grupal
                </div>
                <div class="chat-info">
                    <div class="participants-count" id="participantsCount">0 participantes</div>
                    <div>🟢 En línea</div>
                </div>
            </div>

            @if(session('success'))
                <div class="success-message">
                    <span>✅</span>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Messages -->
            <div class="messages-container">
                @if(count($mensajes) > 0)
                    @foreach($mensajes as $mensaje)
                    <div class="message">
                        <div class="message-header">
                            <div class="message-sender">
                                <div class="sender-avatar">
                                    {{ strtoupper(substr($mensaje->emisor->name, 0, 1)) }}
                                </div>
                                {{ $mensaje->emisor->name }}
                            </div>
                            <div class="message-time">
                                {{ $mensaje->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="message-text">{{ $mensaje->mensaje }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-messages">
                        <div class="empty-icon">💬</div>
                        <div class="empty-title">¡Inicia la conversación!</div>
                        <div class="empty-text">Sé el primero en enviar un mensaje al chat grupal</div>
                    </div>
                @endif
            </div>

            <!-- Message form -->
            <div class="message-form">
                <form action="{{ route('chat.enviar') }}" method="POST" id="groupMessageForm">
                    @csrf
                    <div class="form-container">
                        <textarea 
                            name="mensaje" 
                            class="message-textarea"
                            rows="2" 
                            placeholder="Escribe tu mensaje aquí..." 
                            required
                            id="messageTextarea"
                        ></textarea>
                        <button type="submit" class="send-btn">
                            <span>📤</span>
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-resize textarea
        document.getElementById('messageTextarea').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Auto-scroll al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.querySelector('.messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Actualizar contador de participantes
            const messages = document.querySelectorAll('.message');
            const participantsCount = document.getElementById('participantsCount');
            const onlineCount = document.getElementById('onlineCount');
            
            // Simular conteo de participantes únicos
            const uniqueSenders = new Set();
            messages.forEach(msg => {
                const sender = msg.querySelector('.message-sender').textContent.trim();
                uniqueSenders.add(sender);
            });
            
            const count = uniqueSenders.size || 2; // Mínimo 2 para demo
            participantsCount.textContent = count === 1 ? '1 participante' : `${count} participantes`;
            onlineCount.textContent = count;
        });

        // Envío del formulario con efecto visual
        document.getElementById('groupMessageForm').addEventListener('submit', function(e) {
            const textarea = document.getElementById('messageTextarea');
            const sendBtn = this.querySelector('.send-btn');
            
            // Efecto visual de envío
            sendBtn.style.background = '#168a6b';
            sendBtn.innerHTML = '<span>⏳</span> Enviando...';
            
            // Opcional: resetear después de un momento (si usas AJAX)
            setTimeout(() => {
                textarea.value = '';
                textarea.style.height = 'auto';
            }, 100);
        });

        // Enter para enviar (Shift+Enter para nueva línea)
        document.getElementById('messageTextarea').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('groupMessageForm').submit();
            }
        });
    </script>
</body>
</html>