@extends('admin.layouts.master')

@section('title', 'Panel de administración')

@section('content')
@php
    $quickActions = [
        [
            'title' => 'Inicio',
            'text' => 'Editá banners, textos e imágenes visibles en la portada.',
            'icon' => 'bi-house',
            'route' => route('admin.inicio.edit', ['id' => 1]),
        ],
        [
            'title' => 'Nosotros',
            'text' => 'Actualizá la historia, bloques institucionales e iconos.',
            'icon' => 'bi-building',
            'route' => route('admin.empresa.edit', ['id' => 1]),
        ],
        [
            'title' => 'Masterbatches',
            'text' => 'Gestioná categorías, productos, fichas e imágenes.',
            'icon' => 'bi-box-seam',
            'route' => route('admin.productos.index'),
        ],
        [
            'title' => 'Bobinas y láminas',
            'text' => 'Modificá el contenido de la sección y sus productos.',
            'icon' => 'bi-layers',
            'route' => route('admin.bobinas.index'),
        ],
        [
            'title' => 'Termoformados',
            'text' => 'Editá textos, imágenes y el contenido publicado.',
            'icon' => 'bi-grid-3x3-gap',
            'route' => route('admin.termoformados.edit', ['id' => 1]),
        ],
        [
            'title' => 'Novedades',
            'text' => 'Creá y actualizá artículos visibles en la web.',
            'icon' => 'bi-newspaper',
            'route' => route('admin.novedades.index'),
        ],
        [
            'title' => 'Zona Clientes',
            'text' => 'Habilitá usuarios, vencimientos, base PDF y consultas privadas.',
            'icon' => 'bi-person-badge',
            'route' => route('admin.clientes.index'),
        ],
        [
            'title' => 'Configuración',
            'text' => 'Actualizá logos, contacto, redes sociales y SEO.',
            'icon' => 'bi-gear',
            'route' => route('admin.contacto.edit', ['id' => 1]),
        ],
    ];
@endphp

<div class="admin-page-hero">
    <div>
        <p class="admin-page-kicker">Gestión del sitio</p>
        <h1 class="admin-page-title">Hola, {{ Auth::user()->name }}</h1>
        <p class="admin-page-description">
            Todo texto, imagen o recurso que guardes desde estas secciones queda disponible en la vista pública correspondiente.
        </p>
    </div>

    <a href="{{ route('index') }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
        <i class="bi bi-box-arrow-up-right me-2"></i>
        Ver web pública
    </a>
</div>

<div class="admin-dashboard-grid">
    @foreach($quickActions as $action)
        <article class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon">
                    <i class="bi {{ $action['icon'] }}"></i>
                </div>
                <h2>{{ $action['title'] }}</h2>
                <p>{{ $action['text'] }}</p>
            </div>
            <a href="{{ $action['route'] }}">
                Editar sección
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </article>
    @endforeach
</div>

<div class="admin-workflow">
    <div class="admin-workflow-step">
        <span>1</span>
        <h3>Elegí una sección</h3>
        <p>Usá el menú lateral o los accesos rápidos para entrar al contenido que querés modificar.</p>
    </div>
    <div class="admin-workflow-step">
        <span>2</span>
        <h3>Editá y guardá</h3>
        <p>Actualizá textos, imágenes, productos o datos. Los formularios mantienen la funcionalidad actual.</p>
    </div>
    <div class="admin-workflow-step">
        <span>3</span>
        <h3>Revisá la web</h3>
        <p>Abrí la vista pública para confirmar cómo quedó publicado el cambio.</p>
    </div>
</div>
@endsection
