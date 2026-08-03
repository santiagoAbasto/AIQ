@extends('layouts.app')
@section('title', 'Calidad')
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
/* Breadcrumb dentro del carousel */
.carousel-breadcrumb {
    position: absolute;
    top: 112px;
    left: 0;
    width: 100%;
    z-index: 3; /* arriba del overlay */
}

.breadcrumb-hero {
    background: transparent;
    padding: 0;
}

.breadcrumb-hero .breadcrumb-item {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #ffffff;
}

.breadcrumb-hero .breadcrumb-item a {
    color: #ffffff;
    text-decoration: none;
    opacity: 0.85;
}

.breadcrumb-hero .breadcrumb-item a:hover {
    text-decoration: underline;
    opacity: 1;
}

/* Separador > */
.breadcrumb-hero .breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #ffffff;
    padding: 0 8px;
    font-weight: 300;
    opacity: 0.7;
}

/* Último item */
.breadcrumb-hero .breadcrumb-item.active {
    color: #ffffff;
    opacity: 0.6;
    font-weight: 300;
}
@media (max-width: 768px) {
    .carousel-breadcrumb { display: none; }
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
                    Calidad
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
        
          <h3 class="titulo-secciones">{{$calidad->titulo}}</h3>
          <div class="contenido-empresa custom-item mt-3">{!!$calidad->descripcion!!}</div>
          {{-- <a type="button" href="" class="btn btn__azul mb-2 px-5" >MÁS INFORMACIÓN</a> --}}
   
       </div>
        <div class="col-md-6">
          <div style="background-image: url('{{media_url($calidad->imagen)}}');
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
<style>
    .document-card {
  background-color: #D1D2D44D;
  border: 1px solid #eaeaea;
  transition: all 0.3s ease;
}

.document-card:hover {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  transform: translateY(-2px);
}

.titulo-doc {
  font-family: 'Plus Jakarta Sans';
  font-weight: 700;
  font-style: Bold;
  font-size: 16px;
  line-height: 150%;
  letter-spacing: 0%;
  vertical-align: middle;

}
.doc-icon {
 width: 69px;
    height: 69px;
  object-fit: cover;

}

.download-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 25px;
  height: 25px;
  border-radius: 6px;
  color: #003375;
  transition: all 0.3s ease;
  text-decoration: none;
}



</style>

<div class="container">
  <h1  class="titulo-secciones">Descargas</h1>
    <div class="row">
        @foreach($descargas as $descarga)
          <div class="col-md-6 my-3">
      <div class="document-card d-flex align-items-center justify-content-between p-3 shadow-sm rounded-3">  
        <h6 class="titulo-doc">{{ $descarga->titulo }}</h6>
        <a href="{{ media_url($descarga->pdf) }}" download="" class="download-btn">
           <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none">
  <!-- Línea inferior -->
  <path d="M4 20H20" stroke="#0A66C2" stroke-width="3" stroke-linecap="round"/>
  
  <!-- Flecha -->
  <path d="M12 4V16" stroke="#0A66C2" stroke-width="3" stroke-linecap="round"/>
  <path d="M6 10L12 16L18 10"
        stroke="#0A66C2"
        stroke-width="3"
        stroke-linecap="round"
        stroke-linejoin="round"/>
</svg>

        </a>
      </div>
    </div>
        @endforeach
    </div>
</div>

@endsection
