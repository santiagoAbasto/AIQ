@extends('layouts.app')
@section('title', 'Paletas de Colores')
@section('content')


<style>
    /* Carousel Styles */
.carousel-item {
    height: 680px;
    background-size: cover;
    background-position: center;
    position: relative;
}

/* Overlay para añadir opacidad al carousel */
.carousel-item::before,
.carousel-video-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    background-color: #00000099;
}

.carousel-caption {
    position: absolute;
    top:190px;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
    text-align: left;
    /* padding-left: 10%; */
    z-index: 2; /* Asegurar que el contenido esté por encima del overlay */
}

.carousel__titulo {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-style: 'SemiBold';
    font-size: 48px;
    font-weight: 600;
    line-height: 120%;
    margin-bottom: 1rem;
    color: #FFFFFF; /* Cambiado a blanco para mejor contraste con el overlay */

}

.carousel__descripcion {
    font-size: 1.2rem;
    color: #FFFFFF; /* Cambiado a blanco para mejor contraste con el overlay */
    max-width: 80%;
}


</style>
<div id="carouselExampleIndicators" class="carousel slide position-relative" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">
    <!-- Indicadores -->
    <div class="carousel-indicators justify-content-center">
        @foreach($sliders as $index => $slider)
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    {{-- miga de pan --}}
<div class="carousel-breadcrumb">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-hero mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">Inicio</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Selector de colores
                </li>
            </ol>
        </nav>
    </div>
</div>

    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
            @if(Str::contains($slider->imagen, ['.mp4', '.mov', '.avi']))
                <!-- Elemento - Video -->
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="carousel-video-wrapper">
                        <video class="carousel-video" autoplay loop muted>
                            <source src="{{ media_url($slider->imagen) }}" type="video/mp4">
                            Tu navegador no soporta video HTML5.
                        </video>
                        <div class="carousel-caption text-left">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="carousel__titulo">{{ $slider->titulo }}</h5>
                                        <div class="carousel__descripcion">{!! $slider->descripcion !!}</div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Elemento - Imagen como background -->
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" style="background-image: url('{{ media_url($slider->imagen) }}');
                 height: 400px;
                ">
                    <div class="carousel-caption text-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="carousel__titulo">{{ $slider->titulo }}</h5>
                                    <div class="carousel__descripcion">{!! $slider->descripcion !!}</div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

   



</div>

<style>
    .card-paletas {
    display: block;
    background: #fff;
    border: 1px solid #d9d9d9;
    border-radius: 4px;
    overflow: hidden;
    text-decoration: none;
    transition: all 0.2s ease;
    height: 100%;
}

.card-paletas:hover {
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.card-paletas__image {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #f5f5f5;
}

.card-paletas__image img {
    width: 100%;
    height: 100%;
    object-fit: none;
    display: block;
}

.card-paletas__body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    min-height: 68px;
}

.card-paletas__title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #222;
}

.card-paletas__arrow {
    font-size: 22px;
    color: #cfcfcf;
    line-height: 1;
    transition: transform 0.2s ease, color 0.2s ease;
}

.card-paletas:hover .card-paletas__arrow {
    transform: translateX(4px);
    color: #999;
}
</style>
<div class="container my-5">
    <div class="row">
        @foreach ($paletas as $paleta)

            @php
                $productosJson = $paleta->productos->map(function ($p) {
                    return [
                        'titulo' => $p->titulo,
                        'imagen' => $p->imagen ? media_url($p->imagen) : null,
                        'color' => $p->color,
                        'descripcion' => \Illuminate\Support\Str::limit(strip_tags($p->descripcion ?? ''), 100),
                        'slug' => $p->slug,
                    ];
                })->values()->toArray();
            @endphp

            <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-4">
                <button
                    type="button"
                    class="card-paletas border-0 p-0 w-100 text-start"
                    data-bs-toggle="modal"
                    data-bs-target="#modalPaleta"
                    data-titulo="{{ $paleta->titulo }}"
                    data-color="{{ $paleta->color }}"
                    data-productos='@json($productosJson)'
                >
                    <div class="card-paletas__image">
                        <img src="{{ media_url($paleta->imagen) }}" alt="{{ $paleta->titulo }}">
                      
                    </div>

                    <div class="card-paletas__body">
                        <h5 class="card-paletas__title">{{ $paleta->titulo }}</h5>
                        <span class="card-paletas__arrow">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#E5E5E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                        </span>
                    </div>
                </button>
            </div>
        @endforeach
    </div>
</div>

{{-- Modal paleta de colores --}}
<div class="modal fade" id="modalPaleta" tabindex="-1" aria-labelledby="modalPaletaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalPaletaLabel">Paleta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <div id="modalPaletaProductos" class="row g-4"></div>
                <div id="modalPaletaVacio" class="text-center text-muted py-4 d-none">
                    No hay productos en esta categoría.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalPaleta');

    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const titulo = button.getAttribute('data-titulo') || '';
        const productos = JSON.parse(button.getAttribute('data-productos') || '[]');

        const modalTitle = document.getElementById('modalPaletaLabel');
        const contenedor = document.getElementById('modalPaletaProductos');
        const vacio = document.getElementById('modalPaletaVacio');

        modalTitle.textContent = titulo;
        contenedor.innerHTML = '';

        if (!productos.length) {
            vacio.classList.remove('d-none');
            return;
        }

        vacio.classList.add('d-none');

        productos.forEach(function (producto) {
            const imagen = producto.imagen
                ? `<img src="${producto.imagen}" class="card-img-top" alt="${producto.titulo}" style="height:100px; object-fit:none;">`
                
                : `<div class="bg-light d-flex align-items-center justify-content-center" style="height:100px;">Sin imagen</div>`;

            const colorBadge = producto.color
                ? `<div style="height:36px; background-color:${producto.color}; "></div>`
                : '';

            contenedor.innerHTML += `
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0">
                        ${imagen}
                        ${colorBadge}
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">${producto.titulo}</h6>
                
                        </div>
                    </div>
                </div>
            `;
        });
    });
});
</script>

@endsection






