<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios para chatear</title>
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

        /* Content area */
        .content-area {
            flex: 1;
            padding: 0;
        }

        .users-container {
            background: white;
            margin: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .users-header {
            background: #1db584;
            color: white;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .users-title {
            font-size: 24px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .title-icon {
            font-size: 28px;
        }

        .users-count {
            background: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Users list */
        .users-list {
            list-style: none;
            padding: 0;
        }

        .user-item {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .user-item:hover {
            background: #f8fffe;
            transform: translateY(-1px);
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-link {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #1db584, #0ea471);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .user-status {
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #4CAF50;
            border-radius: 50%;
        }

        .chat-arrow {
            color: #1db584;
            font-size: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .user-item:hover .chat-arrow {
            opacity: 1;
        }

        /* Empty state */
        .empty-state {
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
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .empty-text {
            font-size: 14px;
        }

        /* Welcome message */
        .welcome-panel {
            background: linear-gradient(135deg, #f0f8f5, #e8f5f0);
            margin: 20px;
            padding: 30px;
            border-radius: 12px;
            border-left: 4px solid #1db584;
            text-align: center;
        }

        .welcome-title {
            color: #1db584;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .welcome-text {
            color: #666;
            line-height: 1.5;
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

            .users-container,
            .welcome-panel {
                margin: 10px;
            }

            .header-title {
                display: none;
            }

            .user-link {
                padding: 15px 20px;
            }

            .user-avatar {
                width: 45px;
                height: 45px;
                font-size: 16px;
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
    
        <!-- Content area -->
        <div class="content-area">
            <!-- Welcome panel -->
            <div class="welcome-panel">
                <div class="welcome-title">💬 Centro de Mensajes</div>
                <div class="welcome-text">
                    Selecciona un usuario de la lista para iniciar una conversación privada
                </div>
            </div>

            <!-- Users container -->
            <div class="users-container">
                <div class="users-header">
                    <div class="users-title">
                        <span class="title-icon">👥</span>
                        Usuarios disponibles
                    </div>
                    <div class="users-count" id="usersCount">0 usuarios</div>
                    <a class="back-btn" onclick="history.back()">← Volver</a>
                </div>

                @if(count($usuarios) > 0)
                    <ul class="users-list">
                        @foreach($usuarios as $user)
                            <li class="user-item">
                                <a href="{{ route('chat.privado', $user->id) }}" class="user-link">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-status">
                                            <span class="status-dot"></span>
                                            Disponible para chat
                                        </div>
                                    </div>
                                    <div class="chat-arrow">→</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">😔</div>
                        <div class="empty-title">No hay usuarios disponibles</div>
                        <div class="empty-text">En este momento no hay usuarios registrados para chatear.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Actualizar contador de usuarios
        document.addEventListener('DOMContentLoaded', function() {
            const usersList = document.querySelectorAll('.user-item');
            const usersCount = document.getElementById('usersCount');
            const count = usersList.length;
            
            usersCount.textContent = count === 1 ? '1 usuario' : `${count} usuarios`;
        });

        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            const userItems = document.querySelectorAll('.user-item');
            userItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    item.style.transition = 'all 0.5s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>