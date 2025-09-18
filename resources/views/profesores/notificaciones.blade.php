@extends('layouts.panel')

@section('title', 'Notificaciones')

@section('content')

    
    @section('content')
    <div class="container">
        <h2 style="color:#1E847D; font-family: Montserrat, Verdana, Helvetica, sans-serif; font-size:2.2rem; text-align:center; margin-bottom: 40px; margin-top: 30px; letter-spacing:1px;">
            Notificaciones
        </h2>
        
        <!-- Panel de notificaciones -->
        <div class="notificaciones-container">
            <div class="notificaciones-panel">
                <div class="tabs">
                    <div class="tab active" onclick="showTab('comentarios')">Comentarios</div>
                    <div class="tab" onclick="showTab('comunidad')">Comentarios de la comunidad</div>
                    <div class="tab" onclick="showTab('buzon')">Mi buzón</div>
                    <div class="tab" onclick="showTab('cuadernos')">Mis cuadernos</div>
                </div>
    
                <div class="tab-content" id="tab-content">
                    Aún no hay notificaciones.
                </div>
            </div>
        </div>
    </div>
    @endsection
    
    @push('scripts')
    <script>
        const tabs = document.querySelectorAll('.tab');
        const content = document.getElementById('tab-content');
    
        const tabText = {
            comentarios: 'Aún no hay notificaciones de comentarios.',
            comunidad: 'Aún no hay comentarios de la comunidad.',
            buzon: 'Tu buzón está vacío.',
            cuadernos: 'No hay cuadernos nuevos por el momento.'
        };
    
        function showTab(tab) {
            tabs.forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');
            content.textContent = tabText[tab];
        }
    </script>
    @endpush

@endsection