@php
    $headerOverlay = request()->routeIs(
        'index',
        'empresa',
        'contacto',
        'paletas',
        'novedades',
        'presupuesto',
        'bobinas',
        'productos',
        'productos.subcategoria',
        'termoformados'
    );
    $isClienteLoggedIn = Auth::guard('logincliente')->check();
    $logoMediaExists = function (?string $path): bool {
        if (! $path) {
            return false;
        }

        $normalized = str_replace('\\', '/', trim($path));

        if (preg_match('/^(https?:)?\/\//', $normalized) || str_starts_with($normalized, 'data:')) {
            return true;
        }

        $normalized = ltrim($normalized, '/');
        $storagePath = str_starts_with($normalized, 'public/') ? substr($normalized, 7) : $normalized;

        return file_exists(public_path($normalized))
            || file_exists(public_path('storage/'.ltrim($storagePath, '/')))
            || file_exists(storage_path('app/public/'.ltrim($storagePath, '/')));
    };
    $primaryHeaderLogo = $logo->logo_header ?? null;
    $secondaryHeaderLogo = $logoMediaExists($logo->logo_headerdos ?? null)
        ? $logo->logo_headerdos
        : $primaryHeaderLogo;
    $headerLogoPath = $headerOverlay ? $primaryHeaderLogo : $secondaryHeaderLogo;
@endphp

<nav class="navbar navbar-expand-xl py-0 align-items-center site-header {{ $headerOverlay ? 'site-header--overlay fixed-top' : 'site-header--solid fixed-top shadow-sm' }}" id="mainHeader">
    <div class="header-inner container d-flex align-items-center">

        {{-- Logo --}}
        <a class="navbar-brand p-0 flex-shrink-0 me-0" href="{{ route('index') }}"
           @if($isClienteLoggedIn) data-client-public-logo aria-haspopup="dialog" @endif>
            <img id="navbar-logo" src="{{ media_url($headerLogoPath) }}" class="site-logo" alt="AIQ">
        </a>

        {{-- Botón hamburguesa --}}
        <button class="navbar-toggler bg-white ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav desktop: logo + 16px + links en una sola fila --}}
        <div class="collapse navbar-collapse align-items-center" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center mb-0 flex-nowrap nav-gap ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }} {{ request()->routeIs('empresa') ? 'active__header' : '' }}" href="{{ route('empresa') }}" title="Empresa">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }} {{ request()->routeIs('categorias','productos','productos.subcategoria','producto') ? 'active__header' : '' }}" href="{{ route('productos') }}" title="Productos">Masterbatches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }} {{ request()->routeIs('bobinas') ? 'active__header' : '' }}" href="{{ route('bobinas') }}" title="Bobinas y láminas">Bobinas y láminas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }} {{ request()->routeIs('termoformados') ? 'active__header' : '' }}" href="{{ route('termoformados') }}" title="Termoformados">Termoformados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }}" href="https://liadsmart.com/es/" title="Dosificador de Masterbatches">Dosificador</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }} {{ request()->routeIs('novedades','novedad') ? 'active__header' : '' }}" href="{{ route('novedades') }}" title="Novedades">Novedades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }} {{ request()->routeIs('contacto') ? 'active__header' : '' }}" href="{{ route('contacto') }}" title="Contacto">Contacto</a>
                </li>

                {{-- Buscador --}}
                <li class="nav-item">
                    <a class="nav-link search-modal-trigger {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }}"
                       href="#" title="Buscador"
                       data-bs-toggle="modal" data-bs-target="#searchModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L16.66 16.66M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>                    </a>
                </li>

                {{-- Paleta de colores --}}
                <li class="nav-item">
                    <a class="nav-link btn-presupuesto {{ $headerOverlay ? 'nav__menu__inicio' : 'nav__menu' }} {{ request()->routeIs('paletas') ? 'active__header' : '' }}" href="{{ route('paletas') }}" title="Paleta de colores">Paleta de colores</a>
                </li>

                {{-- Zona Clientes --}}
                <li class="nav-item">
                    @if($isClienteLoggedIn)
                        <a class="nav-link btn-zona-clientes {{ request()->routeIs('cliente.*') ? 'active__header' : '' }}"
                           href="{{ route('cliente.dashboard') }}" title="Mi Panel">Mi Panel</a>
                    @else
                        <a class="nav-link btn-zona-clientes"
                           href="#" title="Zona Clientes"
                           data-bs-toggle="modal" data-bs-target="#loginClienteModal">Zona Clientes</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>

@if(! $headerOverlay)
    <div class="site-header-spacer" aria-hidden="true"></div>
@endif

{{-- =================== OFFCANVAS MÓVIL =================== --}}
<div class="offcanvas offcanvas-end" style="background-color: #131313;" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header">
        <a class="navbar-brand" href="{{ route('index') }}"
           @if($isClienteLoggedIn) data-client-public-logo aria-haspopup="dialog" @endif>
            <img src="{{ media_url($logo->logo_header) }}" class="site-logo site-logo--offcanvas" alt="AIQ">
        </a>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body justify-content-center align-items-center flex-column">
        <ul class="navbar-nav text-center mb-4">
            <li class="nav-item">
                <a class="nav-link nav__menu__inicio {{ request()->routeIs('empresa') ? 'active__header' : '' }}" href="{{ route('empresa') }}">Nosotros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav__menu__inicio {{ request()->routeIs('categorias','productos','productos.subcategoria','producto') ? 'active__header' : '' }}" href="{{ route('productos') }}">Masterbatches</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav__menu__inicio {{ request()->routeIs('bobinas') ? 'active__header' : '' }}" href="{{ route('bobinas') }}">Bobinas y láminas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav__menu__inicio {{ request()->routeIs('termoformados') ? 'active__header' : '' }}" href="{{ route('termoformados') }}">Termoformados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav__menu__inicio {{ request()->routeIs('novedades','novedad') ? 'active__header' : '' }}" href="{{ route('novedades') }}">Novedades</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav__menu__inicio {{ request()->routeIs('contacto') ? 'active__header' : '' }}" href="{{ route('contacto') }}">Contacto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav__menu__inicio {{ request()->routeIs('paletas') ? 'active__header' : '' }}" href="{{ route('paletas') }}">Paleta de colores</a>
            </li>
            <li class="nav-item">
                @if($isClienteLoggedIn)
                    <a class="nav-link nav__menu__inicio {{ request()->routeIs('cliente.*') ? 'active__header' : '' }}" href="{{ route('cliente.dashboard') }}">Mi Panel</a>
                @else
                    <a class="nav-link nav__menu__inicio" href="#"
                       data-bs-dismiss="offcanvas"
                       data-bs-toggle="modal" data-bs-target="#loginClienteModal">Zona Clientes</a>
                @endif
            </li>
        </ul>

        <div class="text-white text-center mt-3 pt-3 border-top border-secondary w-75">
            <div class="mb-3"><i class="fas fa-phone-alt me-2"></i> {{$contacto->telefono}}</div>
            <div class="mb-3"><i class="fas fa-envelope me-2"></i> {{$contacto->correo}}</div>
            <div class="mb-3">
                <button type="button"
                        class="btn btn-link nav-link nav__menu__inicio p-0 text-decoration-none search-modal-trigger"
                        data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fas fa-search me-2"></i> Buscar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =================== MODAL BUSCADOR =================== --}}
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="searchModalLabel">Buscar productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body pt-3">
                <form action="{{ route('buscador') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <img src="{{asset('img/Vector.png')}}" alt="Buscar">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- =================== MODAL LOGIN CLIENTES =================== --}}
<div class="modal fade" id="loginClienteModal" tabindex="-1" aria-labelledby="loginClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                <div>
                    <p class="modal-kicker mb-1">Zona Clientes</p>
                    <h5 class="modal-title fw-bold fs-4" id="loginClienteModalLabel">Acceso privado</h5>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2">
                {{-- Alertas de sesión --}}
                @if(session('success'))
                    <div class="alert alert-success py-2">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger py-2">{{ session('error') }}</div>
                @endif
                @if(! $isClienteLoggedIn && isset($errors) && $errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Formulario Login --}}
                <div id="loginFormWrapper">
                    <form method="POST" action="{{ route('cliente.login.store') }}" id="clienteLoginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="modal_email">Email</label>
                            <input class="form-control" id="modal_email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="modal_password">Contraseña</label>
                            <input class="form-control" id="modal_password" name="password" type="password" autocomplete="current-password" required>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="modal_remember">
                            <label class="form-check-label text-muted" for="modal_remember">Recordarme</label>
                        </div>
                        <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Ingresar</button>
                    </form>
                    <div class="text-center mt-3 pb-2">
                        <span class="text-muted small">¿No tenés cuenta?</span>
                        <button type="button" class="btn btn-link btn-sm p-0 ms-1 text-decoration-none fw-semibold" id="showRegisterForm">Solicitar acceso</button>
                    </div>
                </div>

                {{-- Formulario Registro --}}
                <div id="registerFormWrapper" style="display:none;">
                    <form method="POST" action="{{ route('cliente.register.store') }}" id="clienteRegisterForm">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold" for="reg_name">Nombre</label>
                                <input class="form-control" id="reg_name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold" for="reg_email">Email</label>
                                <input class="form-control" id="reg_email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold" for="reg_company">Empresa</label>
                                <input class="form-control" id="reg_company" name="company" type="text" value="{{ old('company') }}" autocomplete="organization">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold" for="reg_phone">Teléfono</label>
                                <input class="form-control" id="reg_phone" name="phone" type="text" value="{{ old('phone') }}" autocomplete="tel">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold" for="reg_password">Contraseña</label>
                                <input class="form-control" id="reg_password" name="password" type="password" autocomplete="new-password" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-semibold" for="reg_password_confirmation">Confirmar contraseña</label>
                                <input class="form-control" id="reg_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Enviar solicitud</button>
                    </form>
                    <div class="text-center mt-3 pb-2">
                        <span class="text-muted small">¿Ya tenés cuenta?</span>
                        <button type="button" class="btn btn-link btn-sm p-0 ms-1 text-decoration-none fw-semibold" id="showLoginForm">Ingresar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header centrado: 1224px. En viewport 1366 queda margen 71px por lado. */
    #mainHeader .header-inner {
        max-width: 1224px;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        padding-left: 0 !important;
        padding-right: 0 !important;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        height: 100px;
        overflow: visible;
    }

    /* ── ANULAR el flex-direction:column de plantilla.css ── */
    #mainHeader .navbar-nav .nav-link {
        flex-direction: row !important;
        display: inline-flex !important;
        height: auto !important;
        overflow: visible !important;
        visibility: visible !important;
    }
    #mainHeader .navbar-nav .nav-link::after {
        display: none !important;
    }

    /* ── Header base ── */
    .site-header {
        min-height: 100px;
        height: 100px;
        padding: 0 !important;
        z-index: 1030;
        transition: background-color 300ms ease, box-shadow 300ms ease, backdrop-filter 300ms ease;
    }

    .site-header-spacer {
        height: 100px;
        flex: 0 0 100px;
    }

    /* ─────────────────────────────────────────
       OVERLAY — páginas públicas sobre el banner
    ───────────────────────────────────────── */
    .site-header--overlay {
        background: transparent !important;
    }

    /* Todos los links/botones en blanco */
    .site-header--overlay .nav-link,
    .site-header--overlay .nav__menu__inicio,
    .site-header--overlay .btn-presupuesto,
    .site-header--overlay .btn-zona-clientes {
        color: #fff !important;
    }

    /* Buscador SVG en blanco */
    .site-header--overlay .search-modal-trigger svg path {
        stroke: #fff !important;
    }

    /* Botones con borde blanco */
    .site-header--overlay .btn-zona-clientes,
    .site-header--overlay .btn-presupuesto {
        border-color: #fff !important;
    }

    .site-header--overlay .btn-zona-clientes:hover,
    .site-header--overlay .btn-presupuesto:hover {
        background-color: rgba(255,255,255,0.18) !important;
    }

    /* Active en overlay: rojo como referencia */
    .site-header--overlay .active__header {
        color: #FB0D1B !important;
        text-decoration: none !important;
    }

    .site-header--overlay .nav-link:hover,
    .site-header--overlay .nav__menu__inicio:hover {
        color: #FB0D1B !important;
    }

    /* ─────────────────────────────────────────
       SÓLIDO — páginas internas (oscuro)
    ───────────────────────────────────────── */
    .site-header--solid {
        background: #fff;
    }

    .site-header--solid .nav-link,
    .site-header--solid .nav__menu__inicio {
        color: #151414 !important;
    }

    .site-header--solid .nav__menu__inicio.active__header,
    .site-header--solid .nav__menu__inicio:hover {
        color: #FB0D1B !important;
    }

    /* Buscador SVG oscuro en páginas sólidas */
    .site-header--solid .search-modal-trigger svg path {
        stroke: #151414 !important;
    }

    /* ─────────────────────────────────────────
       SCROLLED-BLUR — scroll en todas las secciones
    ───────────────────────────────────────── */
    .scrolled-blur {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.95) !important;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08) !important;
    }

    /* Al hacer scroll, los links quedan en estado oscuro/compacto */
    .scrolled-blur .nav-link,
    .scrolled-blur .nav__menu__inicio {
        color: #151414 !important;
    }

    .scrolled-blur .nav-link.active__header {
        color: #FB0D1B !important;
    }

    .scrolled-blur .nav-link:hover,
    .scrolled-blur .nav__menu__inicio:hover {
        color: #FB0D1B !important;
    }

    /* Buscador SVG oscuro al hacer scroll */
    .scrolled-blur .search-modal-trigger svg path {
        stroke: #151414 !important;
    }

    /* Botones con borde oscuro al hacer scroll */
    .scrolled-blur .btn-zona-clientes,
    .scrolled-blur .btn-presupuesto {
        border-color: #151414 !important;
        color: #151414 !important;
    }

    .scrolled-blur .btn-zona-clientes:hover,
    .scrolled-blur .btn-presupuesto:hover {
        background-color: rgba(0,0,0,0.06) !important;
    }

    /* ─────────────────────────────────────────
       LOGO — animación scroll (solo en overlay/home)
    ───────────────────────────────────────── */
    .site-logo {
        display: block;
        width: 110px;
        height: 110px;
        aspect-ratio: 1 / 1;
        object-fit: contain;
        transition: width 300ms ease, height 300ms ease;
    }

    .scrolled-blur .site-logo {
        width: 80px;
        height: 80px;
    }

    .site-logo--offcanvas {
        width: 90px;
        height: 90px;
        aspect-ratio: 1 / 1;
    }

    /* ─────────────────────────────────────────
       NAV LIST — gap y resets
    ───────────────────────────────────────── */
    ul.nav-gap {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap;
        align-items: center;
        gap: 8px;
        padding: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        margin-left: auto !important;
        margin-right: 0 !important;
        list-style: none;
    }

    ul.nav-gap > .nav-item {
        padding: 0 !important;
        margin: 0 !important;
    }

    ul.nav-gap > .nav-item > .nav-link {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        font-size: 15px;
        white-space: nowrap;
        line-height: 1.2;
    }

    /* Botones pill */
    ul.nav-gap > .nav-item > .btn-presupuesto,
    ul.nav-gap > .nav-item > .btn-zona-clientes {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 9px 12px !important;
        border: 1px solid currentColor !important;
        border-radius: 8px;
        white-space: nowrap;
        font-size: 15px;
        line-height: 1.2;
        margin-left: 0 !important;
        background-color: transparent !important;
    }

    /* Buscador sin padding */
    ul.nav-gap > .nav-item > .search-modal-trigger {
        padding: 0 !important;
        line-height: 1;
    }

    /* Navbar brand sin margin */
    #mainHeader .navbar-brand {
        width: 110px;
        min-width: 110px;
        margin-right: 12px !important;
        overflow: visible;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: none;
    }

    #mainHeader.scrolled-blur .navbar-brand {
        width: 80px;
        min-width: 80px;
    }

    /* Collapse ocupa el espacio restante sin cortar botones ni salir del contenedor */
    #mainHeader .navbar-collapse {
        flex: 1 1 auto;
        min-width: 0;
        overflow: visible;
    }

    .site-header a {
        transition: color 180ms ease, border-color 180ms ease, background-color 180ms ease;
    }

    /* ─────────────────────────────────────────
       MODAL CLIENTES
    ───────────────────────────────────────── */
    .modal-kicker {
        color: #0C58A1;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    @media (max-width: 1255.98px) and (min-width: 1200px) {
        #mainHeader .header-inner {
            width: 100%;
        }

        ul.nav-gap {
            gap: 5px;
        }

        ul.nav-gap > .nav-item > .btn-presupuesto,
        ul.nav-gap > .nav-item > .btn-zona-clientes {
            padding: 8px 9px !important;
        }
    }

    /* ─────────────────────────────────────────
       RESPONSIVE
    ───────────────────────────────────────── */
    @media (max-width: 1199.98px) {
        .site-header {
            min-height: 64px;
            height: 64px;
        }
        .site-header-spacer {
            height: 64px;
            flex-basis: 64px;
        }
        #mainHeader .header-inner {
            padding-left: 24px !important;
            padding-right: 24px !important;
            height: 64px;
            overflow: visible;
        }
        .site-logo,
        .scrolled-blur .site-logo {
            width: 56px;
            height: 56px;
        }
        #mainHeader .navbar-brand,
        #mainHeader.scrolled-blur .navbar-brand {
            width: 56px;
            min-width: 56px;
        }
    }

    @media (max-width: 575.98px) {
        #mainHeader .header-inner {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var header = document.getElementById("mainHeader");
        var searchTriggers = document.querySelectorAll('.search-modal-trigger');
        var offcanvasElement = document.getElementById('offcanvasNavbar');
        var searchModalElement = document.getElementById('searchModal');
        var isScrolled = false;
        var ticking = false;

        // Scroll: aplica el estado compacto al header en todas las secciones.
        function updateHeader() {
            if (!header) return;
            var threshold = isScrolled ? 24 : 72;
            var shouldBeScrolled = window.scrollY > threshold;
            if (shouldBeScrolled !== isScrolled) {
                isScrolled = shouldBeScrolled;
                header.classList.toggle('scrolled-blur', isScrolled);
            }
        }

        if (header) {
            updateHeader();
            window.addEventListener("scroll", function () {
                if (ticking) return;
                ticking = true;
                window.requestAnimationFrame(function () {
                    updateHeader();
                    ticking = false;
                });
            }, { passive: true });
        }

        // Buscador desde offcanvas
        searchTriggers.forEach(function (trigger) {
            trigger.addEventListener('click', function (event) {
                if (trigger.getAttribute('href') === '#') event.preventDefault();
                if (!offcanvasElement || !offcanvasElement.classList.contains('show') || !searchModalElement) return;
                event.preventDefault();
                var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                var modalInstance = bootstrap.Modal.getOrCreateInstance(searchModalElement);
                offcanvasElement.addEventListener('hidden.bs.offcanvas', function handleHidden() {
                    modalInstance.show();
                    offcanvasElement.removeEventListener('hidden.bs.offcanvas', handleHidden);
                });
                if (offcanvasInstance) {
                    offcanvasInstance.hide();
                } else {
                    modalInstance.show();
                }
            });
        });

        // Toggle Login / Register dentro del modal
        var showRegisterBtn = document.getElementById('showRegisterForm');
        var showLoginBtn    = document.getElementById('showLoginForm');
        var loginWrapper    = document.getElementById('loginFormWrapper');
        var registerWrapper = document.getElementById('registerFormWrapper');

        // Las páginas públicas pueden venir del caché del navegador o del hosting.
        // Renovamos el token justo antes de autenticar para evitar un 419 inicial.
        ['clienteLoginForm', 'clienteRegisterForm'].forEach(function (formId) {
            var authForm = document.getElementById(formId);
            if (!authForm) {
                return;
            }

            authForm.addEventListener('submit', async function (event) {
                if (authForm.dataset.csrfReady === 'true') {
                    return;
                }

                event.preventDefault();
                var submitButton = authForm.querySelector('button[type="submit"]');
                var originalLabel = submitButton ? submitButton.innerHTML : '';

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Validando sesión';
                }

                try {
                    var response = await fetch('{{ route('cliente.csrf-token') }}?refresh=' + Date.now(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        cache: 'no-store',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('No se pudo renovar la sesión');
                    }

                    var payload = await response.json();
                    var tokenInput = authForm.querySelector('input[name="_token"]');

                    if (!payload.token || !tokenInput) {
                        throw new Error('Laravel no devolvió un token válido');
                    }

                    tokenInput.value = payload.token;
                    authForm.dataset.csrfReady = 'true';
                    HTMLFormElement.prototype.submit.call(authForm);
                } catch (error) {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalLabel;
                    }

                    var existingAlert = authForm.querySelector('.cliente-session-error');
                    if (!existingAlert) {
                        var alert = document.createElement('div');
                        alert.className = 'alert alert-danger py-2 cliente-session-error';
                        alert.setAttribute('role', 'alert');
                        alert.textContent = 'No pudimos validar la sesión. Cerrá este aviso, volvé a abrirlo e intentá nuevamente.';
                        authForm.prepend(alert);
                    }
                }
            });
        });

        if (showRegisterBtn) {
            showRegisterBtn.addEventListener('click', function () {
                loginWrapper.style.display    = 'none';
                registerWrapper.style.display = 'block';
            });
        }

        if (showLoginBtn) {
            showLoginBtn.addEventListener('click', function () {
                registerWrapper.style.display = 'none';
                loginWrapper.style.display    = 'block';
            });
        }

        // Si hay errores de validación y venimos de register, mostrar form de registro
        @if(! $isClienteLoggedIn && isset($errors) && $errors->any() && old('name'))
            var loginClienteModal = document.getElementById('loginClienteModal');
            if (loginClienteModal && loginWrapper && registerWrapper) {
                loginWrapper.style.display    = 'none';
                registerWrapper.style.display = 'block';
                var modalInstance = bootstrap.Modal.getOrCreateInstance(loginClienteModal);
                modalInstance.show();
            }
        @elseif(! $isClienteLoggedIn && isset($errors) && $errors->any())
            var loginClienteModal = document.getElementById('loginClienteModal');
            if (loginClienteModal) {
                var modalInstance = bootstrap.Modal.getOrCreateInstance(loginClienteModal);
                modalInstance.show();
            }
        @endif

        @if(session('cliente_auth_modal'))
            var loginClienteModal = document.getElementById('loginClienteModal');
            if (loginClienteModal) {
                @if(session('cliente_auth_modal') === 'register')
                    loginWrapper.style.display = 'none';
                    registerWrapper.style.display = 'block';
                @endif
                var modalInstance = bootstrap.Modal.getOrCreateInstance(loginClienteModal);
                modalInstance.show();
            }
        @endif
    });
</script>
