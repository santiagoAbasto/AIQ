<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO -->
    <title>@yield('title', 'AIQ Masterbatches y Aditivos - Fabrica de productos plasticos')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', isset($metadata) ? $metadata->description : '')">  
    <meta name="keywords" content="@yield('keywords', isset($metadata) ? $metadata->keyword : '')">
    <meta name="author" content="AIQ Masterbatches y Aditivos">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Social -->
    <meta property="og:title" content="@yield('title', 'AIQ Masterbatches y Aditivos - Fabrica de productos plasticos')">
    <meta property="og:description" content="@yield('description', isset($metadata) ? $metadata->description : '')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:site_name" content="AIQ Masterbatches y Aditivos - Fabrica de productos plasticos">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet">
    {{-- borrar cache con time --}}
      <link rel="stylesheet" href="{{ asset('css/plantilla.css?'.time()) }}">
    <link rel="stylesheet" href="{{ asset('css/page.css?'.time()) }}">

    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>

  </head>
  
  <body>
    <div id="app">
      @include('page.layouts.header')
      @yield('content')
      @include('page.layouts.footer')
      @if(Auth::guard('logincliente')->check())
        <dialog class="client-exit-dialog" id="clientExitDialog" aria-labelledby="clientExitTitle" aria-describedby="clientExitDescription">
          <div class="client-exit-handle" aria-hidden="true"></div>
          <div class="client-exit-icon" aria-hidden="true">
            <i class="bi bi-shield-lock-fill"></i>
          </div>
          <div class="client-exit-copy">
            <p class="client-exit-label">Sesión privada activa</p>
            <h2 id="clientExitTitle">¿Querés volver al sitio público?</h2>
            <p id="clientExitDescription">Para proteger tus consultas y tu historial, primero tenés que cerrar la sesión de Zona Clientes.</p>
          </div>
          <div class="client-exit-actions">
            <button type="button" class="client-exit-stay" data-client-exit-close>Seguir en mi panel</button>
            <form method="POST" action="{{ route('cliente.logout') }}">
              @csrf
              <button type="submit" class="client-exit-confirm">
                Cerrar sesión y volver
                <i class="bi bi-arrow-right"></i>
              </button>
            </form>
          </div>
        </dialog>

        <style>
          .footer-logo-link { display: inline-block; }
          .client-exit-dialog {
            background: rgba(250, 251, 253, .96);
            border: 1px solid rgba(255, 255, 255, .92);
            border-radius: 28px;
            box-shadow: 0 30px 90px rgba(5, 22, 42, .28), 0 2px 12px rgba(5, 22, 42, .1);
            color: #101828;
            margin: auto;
            max-width: 430px;
            opacity: 0;
            padding: 28px;
            transform: translateY(12px) scale(.94);
            transition:
              opacity 240ms cubic-bezier(.22, 1, .36, 1),
              transform 240ms cubic-bezier(.22, 1, .36, 1),
              overlay 240ms allow-discrete,
              display 240ms allow-discrete;
            width: calc(100% - 32px);
          }
          .client-exit-dialog[open] { opacity: 1; transform: translateY(0) scale(1); }
          @starting-style {
            .client-exit-dialog[open] { opacity: 0; transform: translateY(12px) scale(.94); }
          }
          .client-exit-dialog::backdrop {
            background: rgba(4, 17, 31, .42);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            opacity: 0;
            transition:
              opacity 240ms cubic-bezier(.22, 1, .36, 1),
              overlay 240ms allow-discrete,
              display 240ms allow-discrete;
          }
          .client-exit-dialog[open]::backdrop { opacity: 1; }
          @starting-style { .client-exit-dialog[open]::backdrop { opacity: 0; } }
          .client-exit-handle { display: none; }
          .client-exit-icon {
            align-items: center;
            background: #e8f2fb;
            border: 1px solid #d5e6f6;
            border-radius: 18px;
            color: #0c58a1;
            display: flex;
            font-size: 24px;
            height: 58px;
            justify-content: center;
            margin-bottom: 22px;
            width: 58px;
          }
          .client-exit-label {
            color: #0c58a1;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .04em;
            margin: 0 0 7px;
            text-transform: uppercase;
          }
          .client-exit-copy h2 { color: #101828; font-size: 23px; font-weight: 800; letter-spacing: -.02em; line-height: 1.2; margin: 0; }
          .client-exit-copy > p:last-child { color: #5c6879; font-size: 14px; line-height: 1.6; margin: 12px 0 0; }
          .client-exit-actions { display: grid; gap: 9px; margin-top: 25px; }
          .client-exit-actions button {
            align-items: center;
            border-radius: 14px;
            display: flex;
            font-size: 14px;
            font-weight: 750;
            justify-content: center;
            min-height: 49px;
            padding: 0 18px;
            transition: background-color 150ms ease, transform 150ms cubic-bezier(.22, 1, .36, 1);
            width: 100%;
          }
          .client-exit-actions button:active { transform: scale(.97); }
          .client-exit-confirm { background: #0c58a1; border: 1px solid #0c58a1; color: #fff; gap: 10px; }
          .client-exit-confirm:hover { background: #08477f; }
          .client-exit-stay { background: #fff; border: 1px solid #d7dee8; color: #273448; }
          .client-exit-stay:hover { background: #f3f6f9; }
          .client-exit-actions button:focus-visible { outline: 3px solid rgba(12, 88, 161, .25); outline-offset: 2px; }
          @media (max-width: 575px) {
            .client-exit-dialog {
              border-radius: 28px 28px 0 0;
              margin: auto 0 0;
              max-width: none;
              padding: 14px 22px calc(24px + env(safe-area-inset-bottom));
              transform: translateY(100%);
              width: 100%;
            }
            .client-exit-dialog[open] { transform: translateY(0); }
            @starting-style { .client-exit-dialog[open] { transform: translateY(100%); } }
            .client-exit-handle { background: #cbd3dd; border-radius: 999px; display: block; height: 5px; margin: 0 auto 20px; width: 42px; }
          }
          @media (prefers-reduced-motion: reduce) {
            .client-exit-dialog, .client-exit-dialog::backdrop, .client-exit-actions button {
              transition-duration: 1ms;
            }
          }
        </style>
      @endif
      <style>

.whatsapp {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        background-color: #25D366;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
      }
      </style>

      <a href="https://api.whatsapp.com/send?phone={{ preg_replace('/[^0-9]/', '', $contacto->whatsapp) }}" class="whatsapp" target="_blank"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M23.3317 19.176C22.9325 18.9774 20.9751 18.02 20.6107 17.8867C20.2463 17.7547 19.981 17.6894 19.7144 18.0867C19.4491 18.4814 18.6868 19.3747 18.4551 19.6387C18.2219 19.904 17.9902 19.936 17.5923 19.7387C17.1943 19.5387 15.9109 19.1214 14.3903 17.772C13.2073 16.7214 12.4074 15.424 12.1756 15.0267C11.9439 14.6307 12.1502 14.416 12.3498 14.2187C12.5293 14.0414 12.7477 13.756 12.9473 13.5254C13.147 13.2934 13.2126 13.128 13.3452 12.8627C13.4792 12.5987 13.4122 12.368 13.3118 12.1694C13.2126 11.9707 12.4168 10.02 12.0845 9.22671C11.7617 8.45471 11.4334 8.56004 11.1896 8.54671C10.9565 8.53604 10.6912 8.53337 10.4259 8.53337C10.1607 8.53337 9.72926 8.63204 9.36485 9.02937C8.9991 9.42537 7.97151 10.384 7.97151 12.3347C7.97151 14.284 9.39701 16.168 9.59663 16.4334C9.79625 16.6974 12.4034 20.7 16.3972 22.416C17.3484 22.824 18.0893 23.068 18.6667 23.2493C19.6206 23.552 20.4888 23.5093 21.1747 23.4067C21.9384 23.2933 23.53 22.448 23.8623 21.5227C24.1932 20.5974 24.1932 19.804 24.0941 19.6387C23.9949 19.4734 23.7296 19.3747 23.3304 19.176H23.3317ZM16.0676 29.0467H16.0623C13.6901 29.0471 11.3616 28.4125 9.32064 27.2093L8.83833 26.924L3.82499 28.2333L5.1634 23.3693L4.84855 22.8707C3.52239 20.7698 2.82057 18.3384 2.82419 15.8574C2.82687 8.59071 8.76732 2.67872 16.073 2.67872C19.6099 2.67872 22.9352 4.05205 25.4352 6.54271C26.6682 7.76482 27.6456 9.21813 28.3106 10.8186C28.9757 12.419 29.3153 14.1348 29.3097 15.8667C29.307 23.1333 23.3666 29.0467 16.0676 29.0467ZM27.3376 4.65071C25.8614 3.17195 24.1051 1.99943 22.1703 1.20112C20.2355 0.402806 18.1608 -0.00543499 16.0663 5.46366e-05C7.28556 5.46366e-05 0.136654 7.11338 0.133975 15.856C0.129906 18.6384 0.863301 21.3725 2.26016 23.7827L0 32L8.44578 29.7947C10.7821 31.0615 13.4003 31.7253 16.0609 31.7253H16.0676C24.8483 31.7253 31.9972 24.612 31.9999 15.868C32.0064 13.7844 31.5977 11.7202 30.7974 9.79475C29.9971 7.86933 28.8212 6.12094 27.3376 4.65071Z" fill="white"/>
</svg>
      </a>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
      AOS.init();
    </script>
    @if(Auth::guard('logincliente')->check())
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          const dialog = document.getElementById('clientExitDialog');
          if (!dialog) return;

          document.querySelectorAll('[data-client-public-logo]').forEach(function (logo) {
            logo.addEventListener('click', function (event) {
              event.preventDefault();

              const openOffcanvas = logo.closest('.offcanvas');
              if (openOffcanvas && window.bootstrap) {
                bootstrap.Offcanvas.getOrCreateInstance(openOffcanvas).hide();
              }

              if (!dialog.open) dialog.showModal();
            });
          });

          dialog.querySelector('[data-client-exit-close]')?.addEventListener('click', function () {
            dialog.close();
          });

          dialog.addEventListener('click', function (event) {
            if (event.target === dialog) dialog.close();
          });
        });
      </script>
    @endif
    @stack('scripts')
  </body>
</html>


