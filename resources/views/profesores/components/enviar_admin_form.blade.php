<div class="enviar-admin-form">
    <form id="formEnviarAdmin" method="POST" action="{{ route('profesores.enviar.a.admin') }}">
        @csrf
        <div class="mb-2">
            <label for="mensajeCoordinador">Mensaje al coordinador</label>
            <textarea id="mensajeCoordinador" name="mensaje" class="form-control" rows="4" required></textarea>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>
    <div id="enviarAdminAlert" style="display:none;" class="mt-2"></div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('formEnviarAdmin');
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const data = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: data
                    }).then(r => r.json()).then(json => {
                        const alert = document.getElementById('enviarAdminAlert');
                        if (json.success) {
                            alert.style.display = 'block';
                            alert.className = 'alert alert-success';
                            alert.innerText = 'Mensaje enviado correctamente';
                            // cerrar modal después de 1.2s
                            setTimeout(() => {
                                const modalEl = document.getElementById('modalEnviarAdmin');
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                modal.hide();
                                alert.style.display = 'none';
                                form.reset();
                            }, 1200);
                        } else {
                            alert.style.display = 'block';
                            alert.className = 'alert alert-danger';
                            alert.innerText = json.message || 'Error al enviar';
                        }
                    }).catch(err => {
                        const alert = document.getElementById('enviarAdminAlert');
                        alert.style.display = 'block';
                        alert.className = 'alert alert-danger';
                        alert.innerText = 'Error de comunicación';
                    });
                });
            });
        </script>
    @endpush
</div>
