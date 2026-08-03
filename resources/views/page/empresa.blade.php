@extends('layouts.app')
@section('title', 'Empresa')
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
                    Nosotros
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
    
      <div class="col-md-6">
        
          <h1 class="titulo-secciones">{{$empresa->titulo}}</h1>
          <div class="contenido-empresa custom-item mt-3">{!!$empresa->descripcion!!}</div>
          {{-- <a type="button" href="" class="btn btn__azul mb-2 px-5" >MÁS INFORMACIÓN</a> --}}
   
       </div>
        <div class="col-md-6">
          <div style="background-image: url('{{media_url($empresa->imagen)}}');
          background-repeat:no-repeat;
          background-position:center;
          background-size:cover;
          height:500px;
          border-radius:8px;
          ">

          </div>
      </div>
     
    
  </div>

</div>



{{-- =========================
     MISIÓN / VISIÓN / VALORES
========================= --}}
<section style="background-color:#F5F5F5;">
    <div class="container py-5">
 <h1 class="titulo-secciones">¿Por que elegirnos?</h1>
        <div class="row g-4 mt-3">

            {{-- MISIÓN --}}
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-duration="1500">
                <div class="bg__card w-100">
                    <div class="card__contenido">
                        <img
                            src="{{ media_url($empresa->icono_mision) }}"
                            class="card__icono"
                            alt="Misión">

                        <h3 class="card__titulo my-3">
                            Misión
                        </h3>

                        <div class="card__texto">
                            {!! $empresa->texto_mision !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- VISIÓN --}}
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-duration="1500">
                <div class="bg__card w-100">
                    <div class="card__contenido">
                        <img
                            src="{{ media_url($empresa->icono_vision) }}"
                            class="card__icono"
                            alt="Visión">

                        <h3 class="card__titulo my-3">
                            Visión
                        </h3>

                        <div class="card__texto">
                            {!! $empresa->texto_vision !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- VALORES --}}
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-duration="1500">
                <div class="bg__card w-100">
                    <div class="card__contenido">
                        <img
                            src="{{ media_url($empresa->icono_valores) }}"
                            class="card__icono"
                            alt="Valores">

                        <h3 class="card__titulo my-3">
                            Valores
                        </h3>

                        <div class="card__texto">
                            {!! $empresa->texto_valores !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>





@endsection
