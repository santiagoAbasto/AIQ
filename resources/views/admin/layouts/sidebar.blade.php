@php
    $adminLogo = \App\Models\Logo::query()->first();
    $adminLogoPath = $adminLogo?->logo_header ?: $adminLogo?->logo_footer;
    $sliderSection = request()->route('seccion');

    $publicSections = [
        [
            'id' => 'collapseHome',
            'label' => 'Inicio',
            'icon' => 'bi-house',
            'active' => (request()->routeIs('admin.slider.*') && $sliderSection === 'inicio') || request()->routeIs('admin.inicio.*'),
            'links' => [
                ['label' => 'Slider principal', 'route' => route('admin.slider.index', ['seccion' => 'inicio']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'inicio'],
                ['label' => 'Textos e imágenes', 'route' => route('admin.inicio.edit', ['id' => 1]), 'active' => request()->routeIs('admin.inicio.*')],
            ],
        ],
        [
            'id' => 'collapseEmpresa',
            'label' => 'Nosotros',
            'icon' => 'bi-building',
            'active' => (request()->routeIs('admin.slider.*') && $sliderSection === 'empresa') || request()->routeIs('admin.empresa.*'),
            'links' => [
                ['label' => 'Banner / slider', 'route' => route('admin.slider.index', ['seccion' => 'empresa']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'empresa'],
                ['label' => 'Contenido público', 'route' => route('admin.empresa.edit', ['id' => 1]), 'active' => request()->routeIs('admin.empresa.*')],
            ],
        ],
        [
            'id' => 'collapseProductos',
            'label' => 'Masterbatches',
            'icon' => 'bi-box-seam',
            'active' => request()->routeIs('admin.categorias.*', 'admin.subcategorias.*', 'admin.productos.*', 'admin.caracteristicas.*') || (request()->routeIs('admin.slider.*') && $sliderSection === 'productos'),
            'links' => [
                ['label' => 'Banner / slider', 'route' => route('admin.slider.index', ['seccion' => 'productos']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'productos'],
                ['label' => 'Categorías', 'route' => route('admin.categorias.index'), 'active' => request()->routeIs('admin.categorias.*')],
                ['label' => 'Subcategorías', 'route' => route('admin.subcategorias.index'), 'active' => request()->routeIs('admin.subcategorias.*')],
                ['label' => 'Productos', 'route' => route('admin.productos.index'), 'active' => request()->routeIs('admin.productos.*')],
                ['label' => 'Características', 'route' => route('admin.caracteristicas.index'), 'active' => request()->routeIs('admin.caracteristicas.*')],
            ],
        ],
        [
            'id' => 'collapseBobinas',
            'label' => 'Bobinas y láminas',
            'icon' => 'bi-layers',
            'active' => request()->routeIs('admin.bobinas.*', 'admin.contenido_bobina.*') || (request()->routeIs('admin.slider.*') && $sliderSection === 'bobinas'),
            'links' => [
                ['label' => 'Banner / slider', 'route' => route('admin.slider.index', ['seccion' => 'bobinas']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'bobinas'],
                ['label' => 'Contenido público', 'route' => route('admin.contenido_bobina.edit', ['id' => 1]), 'active' => request()->routeIs('admin.contenido_bobina.*')],
                ['label' => 'Gestionar bobinas', 'route' => route('admin.bobinas.index'), 'active' => request()->routeIs('admin.bobinas.*')],
            ],
        ],
        [
            'id' => 'collapseTermoformados',
            'label' => 'Termoformados',
            'icon' => 'bi-grid-3x3-gap',
            'active' => request()->routeIs('admin.termoformados.*') || (request()->routeIs('admin.slider.*') && $sliderSection === 'termoformados'),
            'links' => [
                ['label' => 'Banner / slider', 'route' => route('admin.slider.index', ['seccion' => 'termoformados']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'termoformados'],
                ['label' => 'Contenido público', 'route' => route('admin.termoformados.edit', ['id' => 1]), 'active' => request()->routeIs('admin.termoformados.*')],
            ],
        ],
        [
            'id' => 'collapseNovedades',
            'label' => 'Novedades',
            'icon' => 'bi-newspaper',
            'active' => request()->routeIs('admin.novedades.*') || (request()->routeIs('admin.slider.*') && $sliderSection === 'novedades'),
            'links' => [
                ['label' => 'Banner / slider', 'route' => route('admin.slider.index', ['seccion' => 'novedades']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'novedades'],
                ['label' => 'Artículos', 'route' => route('admin.novedades.index'), 'active' => request()->routeIs('admin.novedades.*')],
            ],
        ],
    ];

    $managementSections = [
        [
            'id' => 'collapseClientes',
            'label' => 'Zona Clientes',
            'icon' => 'bi-person-badge',
            'active' => request()->routeIs('admin.clientes.*', 'admin.integrations.*'),
            'links' => [
                ['label' => 'Gestión de clientes', 'route' => route('admin.clientes.index'), 'active' => request()->routeIs('admin.clientes.index', 'admin.clientes.create', 'admin.clientes.edit', 'admin.clientes.imports')],
                ['label' => 'Base de conocimiento IA', 'route' => route('admin.clientes.knowledge'), 'active' => request()->routeIs('admin.clientes.knowledge', 'admin.clientes.knowledge.*')],
                ['label' => 'Integraciones IA', 'route' => route('admin.integrations.edit'), 'active' => request()->routeIs('admin.integrations.*')],
                ['label' => 'Consultas IA', 'route' => route('admin.clientes.ai'), 'active' => request()->routeIs('admin.clientes.ai')],
            ],
        ],
        [
            'id' => 'collapseConfiguracion',
            'label' => 'Configuración pública',
            'icon' => 'bi-gear',
            'active' => request()->routeIs('admin.contacto.*', 'admin.logos.*', 'admin.redes.*') || (request()->routeIs('admin.slider.*') && in_array($sliderSection, ['contacto', 'paletas'], true)),
            'links' => [
                ['label' => 'Slider contacto', 'route' => route('admin.slider.index', ['seccion' => 'contacto']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'contacto'],
                ['label' => 'Slider paletas', 'route' => route('admin.slider.index', ['seccion' => 'paletas']), 'active' => request()->routeIs('admin.slider.*') && $sliderSection === 'paletas'],
                ['label' => 'Datos de contacto', 'route' => route('admin.contacto.edit', ['id' => 1]), 'active' => request()->routeIs('admin.contacto.*')],
                ['label' => 'Logos', 'route' => route('admin.logos.edit', ['id' => 1]), 'active' => request()->routeIs('admin.logos.*')],
                ['label' => 'Redes sociales', 'route' => route('admin.redes.edit', ['id' => 1]), 'active' => request()->routeIs('admin.redes.*')],
            ],
        ],
    ];

    if (Auth::user()?->role === 'Administrador') {
        $managementSections[] = [
            'id' => 'collapseUsuarios',
            'label' => 'Usuarios',
            'icon' => 'bi-people',
            'active' => request()->routeIs('admin.users.*'),
            'links' => [
                ['label' => 'Lista de usuarios', 'route' => route('admin.users.index'), 'active' => request()->routeIs('admin.users.*')],
            ],
        ];
    }

    $singleLinks = [
        ['label' => 'Newsletter', 'icon' => 'bi-envelope-paper', 'route' => route('admin.newsletter.index'), 'active' => request()->routeIs('admin.newsletter.*')],
        ['label' => 'Mensajes', 'icon' => 'bi-chat-left-text', 'route' => route('admin.contactomensaje.index'), 'active' => request()->routeIs('admin.contactomensaje.*')],
        ['label' => 'Metadatos SEO', 'icon' => 'bi-tags', 'route' => route('admin.metadatos.index'), 'active' => request()->routeIs('admin.metadatos.*')],
    ];
@endphp

<nav id="sidebar" class="sidebar shadow-right" aria-label="Administrador">
    <ul class="sidebar-nav nav accordion">
        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-brand">
            @if($adminLogoPath)
                <img src="{{ media_url($adminLogoPath) }}" class="admin-sidebar-logo" alt="AIQ">
            @else
                <span class="admin-sidebar-mark">AIQ</span>
            @endif
            <span>
                <span class="admin-sidebar-title">Panel AIQ</span>
                <span class="admin-sidebar-subtitle">Contenido público</span>
            </span>
        </a>

        <div class="admin-sidebar-section">Editar sitio</div>
        @foreach($publicSections as $section)
            <li class="sidebar-item {{ $section['active'] ? 'is-active' : '' }}">
                <a href="#"
                   class="nav-link {{ $section['active'] ? '' : 'collapsed' }}"
                   data-bs-toggle="collapse"
                   data-bs-target="#{{ $section['id'] }}"
                   aria-expanded="{{ $section['active'] ? 'true' : 'false' }}"
                   aria-controls="{{ $section['id'] }}">
                    <div class="nav-link-icon"><i class="bi {{ $section['icon'] }}"></i></div>
                    <span>{{ $section['label'] }}</span>
                    <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
                </a>
                <div id="{{ $section['id'] }}" class="collapse {{ $section['active'] ? 'show' : '' }}">
                    <nav class="sidenav-menu-nested nav">
                        @foreach($section['links'] as $link)
                            <a href="{{ $link['route'] }}" class="nav-link {{ $link['active'] ? 'active' : '' }}">{{ $link['label'] }}</a>
                        @endforeach
                    </nav>
                </div>
            </li>
        @endforeach

        <div class="admin-sidebar-section">Operación</div>
        @foreach($managementSections as $section)
            <li class="sidebar-item {{ $section['active'] ? 'is-active' : '' }}">
                <a href="#"
                   class="nav-link {{ $section['active'] ? '' : 'collapsed' }}"
                   data-bs-toggle="collapse"
                   data-bs-target="#{{ $section['id'] }}"
                   aria-expanded="{{ $section['active'] ? 'true' : 'false' }}"
                   aria-controls="{{ $section['id'] }}">
                    <div class="nav-link-icon"><i class="bi {{ $section['icon'] }}"></i></div>
                    <span>{{ $section['label'] }}</span>
                    <div class="sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
                </a>
                <div id="{{ $section['id'] }}" class="collapse {{ $section['active'] ? 'show' : '' }}">
                    <nav class="sidenav-menu-nested nav">
                        @foreach($section['links'] as $link)
                            <a href="{{ $link['route'] }}" class="nav-link {{ $link['active'] ? 'active' : '' }}">{{ $link['label'] }}</a>
                        @endforeach
                    </nav>
                </div>
            </li>
        @endforeach

        @foreach($singleLinks as $link)
            <li class="sidebar-item {{ $link['active'] ? 'is-active' : '' }}">
                <a href="{{ $link['route'] }}" class="nav-link">
                    <div class="nav-link-icon"><i class="bi {{ $link['icon'] }}"></i></div>
                    <span>{{ $link['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
