@extends('layouts.app')
@section('title', 'Novedades')
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
                    Novedades
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
<div class="bg__novedades">

    <div class="container my-5" data-aos="fade-up" data-aos-duration="1000">
        <div class="row mt-5">
            @foreach ($novedades as $index => $novedad)
           <div class="col-md-4 mb-4"
             data-aos="fade-up"
             data-aos-delay="{{ $index * 150 }}"
             data-aos-duration="800">

          <div class="card blog-card h-100">
            <a href="{{ route('novedad', $novedad->id) }}"
               class="text-decoration-none text-dark d-flex flex-column h-100">

              {{-- Imagen --}}
              <div class="blog-card__img"
                   style="background-image:url('{{ media_url($novedad->imagen) }}');">
              </div>

              {{-- Contenido --}}
              <div class="card-body-flex bg-white">
                <div>
                  <h5 class="card-categoria">{{ $novedad->categoria }}</h5>
                  <h5 class="card-title">{{ $novedad->titulo }}</h5>
                  <div class="card-text-corto">
                    {!! Str::limit(strip_tags($novedad->descripcion), 150, '...') !!}
                  </div>
                </div>

                {{-- Footer card --}}
                <div class="d-flex justify-content-between align-items-center pt-2">
                  <span class="card-date">Leer más</span>

                  <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg"
                       width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M4 12H18" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>
                    <path d="M12 6L18 12L12 18" stroke="currentColor" stroke-width="1"
                          stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
              </div>

            </a>
          </div>

        </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
