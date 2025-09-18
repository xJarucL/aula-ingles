@extends('layouts.app')

@section('content')
    <div class="container">
        <h2
            style="color:#1E847D; font-family: Montserrat, Verdana, Helvetica, sans-serif; font-size:2.2rem; text-align:center; margin-bottom: 40px; margin-top: 60px; letter-spacing:1px;">
            Bienvenido a la plataforma de actividades
        </h2>

        <!-- Formulario de login -->
        <div class="login-form-container">
            <div class="login-form">
                <h3>Inicio de sesión</h3>
                <p>Accede con tu cuenta para continuar</p>

                <div id="googleBtn"></div>

<script>
  const GOOGLE_CID = @json(config('services.google.client_id'));
  let submitting = false;

  function _gsiInit() {
    if (!GOOGLE_CID) {
      console.warn('Sin GOOGLE_CLIENT_ID en config/services.php o .env');
      return;
    }
    if (!window.google || !google.accounts || !google.accounts.id) {
      console.warn('GSI no cargó. Revisa bloqueadores o red.');
      return;
    }
    google.accounts.id.initialize({
      client_id: GOOGLE_CID,
      callback: (resp) => {
        if (submitting) return;
        submitting = true;

        const f = document.createElement('form');
        f.method = 'POST';
        f.action = @json(route('google.onetap.handle'));

        const i = document.createElement('input');
        i.type = 'hidden';
        i.name = 'credential';
        i.value = resp.credential;
        f.appendChild(i);

        document.body.appendChild(f);
        f.submit();

        // Evita spam si la red está lenta
        setTimeout(() => { submitting = false; }, 8000);
      },
      auto_select: false,
      cancel_on_tap_outside: false,
      context: 'signin'
    });

    google.accounts.id.renderButton(document.getElementById('googleBtn'), {
      theme: 'filled_black',
      size: 'large',
      text: 'continue_with',
      shape: 'pill',
      logo_alignment: 'left'
    });
  }
  window._gsiInit = _gsiInit;
</script>
<script src="https://accounts.google.com/gsi/client" async defer onload="_gsiInit()"></script>

            </div>
        </div>
    </div>
@endsection