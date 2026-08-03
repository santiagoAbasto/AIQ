<!-- filepath: c:\laragon\www\powercom\resources\views\page\categoria-productos.blade.php -->
@extends('layouts.app')
@section('title', $categoria ? $categoria->titulo : 'Productos')
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
                    Masterbatches
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

<div class="container my-5">
    <div class="row">
        {{-- SIDEBAR DE CATEGORÍAS --}}
        <div class="col-md-3 mb-4">
            
            {{-- BUSCADOR --}}
            <form action="{{ route('buscador') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <img src="{{asset('img/Vector.png')}}" alt="Buscar">
                    </button>
                </div>
            </form>    
            
   @foreach($categorias as $cat)
    @php
        $catActiva = isset($categoria) && $categoria->id == $cat->id;
        $subActiva = isset($subcategoria) && $cat->subcategorias->contains('id', $subcategoria->id);
        $abierto = $catActiva || $subActiva;
    @endphp

    <div class="sidebar-item">
        @if($cat->subcategorias->count())
            <div 
                class="sidebar-categoria {{ $abierto ? 'active' : '' }}"
                onclick="toggleSidebarMenu('cat-{{ $cat->id }}', this)"
            >
                <span>{{ $cat->titulo }}</span>

                <svg class="sidebar-arrow {{ $abierto ? 'rotate' : '' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M6 9L12 15L18 9" stroke="#C7C7C7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                {{-- <svg class="sidebar-arrow {{ $abierto ? 'rotate' : '' }}" width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 2L9 9L16 2" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg> --}}
            </div>

            <div id="cat-{{ $cat->id }}" class="sidebar-subcategorias {{ $abierto ? 'open' : '' }}">
                

                @foreach($cat->subcategorias as $sub)
                    <a href="{{ route('productos.subcategoria', [$cat->slug, $sub->slug]) }}"
                       class="sidebar-subcategoria {{ (isset($subcategoria) && $subcategoria->id == $sub->id) ? 'active' : '' }}">
                        {{ $sub->titulo }}
                    </a>
                @endforeach
            </div>
        @else
            <a href="{{ route('productos', $cat->slug) }}"
               class="sidebar-categoria {{ $catActiva ? 'active' : '' }}">
                <span>{{ $cat->titulo }}</span>
            </a>
        @endif
    </div>
@endforeach
        </div>

        <style>

        </style>

        {{-- LISTADO DE PRODUCTOS --}}
        <div class="col-md-9">
        
                {{-- VISTA FILTRADA POR CATEGORÍA SELECCIONADA --}}
                <div class="row">
    @foreach($productos as $producto)
      <div class="col-12 col-md-4 mb-4">
        <a href="{{ route('contacto') }}" class="text-decoration-none">
          <div class="producto-card h-100">

            <div class="producto-card__img-wrapper">
              <img
                src="{{ media_url($producto->imagen) }}"
                alt="{{ $producto->titulo }}"
                class="producto-card__img"
              >
            </div>

            <div class="producto-card__body">
              <div class="producto-card__categoria">
               Masterbatches - {{ $producto->relaciones->first()?->categoria?->titulo ?? '—' }} 
              </div>

              <div class="producto-card__titulo">
                {{ $producto->titulo }}
              </div>

              <div class="producto-card__texto">
                {!! Str::limit(strip_tags($producto->descripcion), 100) !!}
              </div>

             <div class="producto-card__cta">
    <div class="producto-card__feature">
        @if($producto->caracteristica)
            <img src="{{ media_url($producto->caracteristica->imagen) }}" alt="" style="width:24px; height:24px; margin-right:8px;">
            <span class="producto-card__caracteristica">{{ $producto->caracteristica->titulo }}</span>
        @else
            <span>&nbsp;</span>
        @endif
    </div>

    <a href="{{ route('contacto') }}" class="btn-ver-mas">Consultar</a>
</div>
            </div>

          </div>
        </a>
      </div>
    @endforeach
</div>

          
        </div>
       
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleSidebarMenu(id, element) {
        const menu = document.getElementById(id);
        const arrow = element.querySelector('.sidebar-arrow');
        if (menu.classList.contains('open')) {
            menu.classList.remove('open');
            arrow.classList.remove('rotate');
        } else {
            menu.classList.add('open');
            arrow.classList.add('rotate');
        }
    }
</script>
@endpush
