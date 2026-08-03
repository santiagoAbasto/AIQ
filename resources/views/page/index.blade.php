@extends('layouts.app')

@section('content')


<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
    <!-- Indicadores en la parte superior (opcional) -->
    <div class="carousel-indicators">
        @foreach($sliders as $index => $slider)
            <button type="button" 
                    data-bs-target="#carouselExampleIndicators" 
                    data-bs-slide-to="{{ $index }}" 
                    class="{{ $index == 0 ? 'active' : '' }}" 
                    aria-current="{{ $index == 0 ? 'true' : 'false' }}" 
                    aria-label="Slide {{ $index + 1 }}">
            </button>
        @endforeach
    </div>

    <!-- Contenido del carousel -->
    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
            @if(Str::contains($slider->imagen, ['.mp4', '.mov', '.avi']))
                <!-- Elemento - Video -->
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="carousel-video-wrapper position-relative">
                        <div class="carousel-overlay"></div> <!-- Nuevo overlay -->
                        <video class="carousel-video w-100" autoplay loop muted playsinline>
                            <source src="{{ media_url($slider->imagen) }}" type="video/mp4">
                            Tu navegador no soporta video HTML5.
                        </video>
                        
                        <!-- Overlay para el contenido -->
                        <div class="carousel-caption d-flex align-items-end align-items-md-center justify-content-start h-100 pb-5 pb-md-0">
                            <div class="aiq-container text-start">
                               <div class="col-md-6">
                                <h1 class="carousel-title  text-white ">
                                    {{ $slider->titulo }}
                                </h1>
                                <div class="carousel-subtitle  text-white mb-sm-5 mb-4 mt-2">
                                    {!! $slider->descripcion !!}
                                </div>
                                <a href="{{ route('contacto') }}" class="btn-inicio">
                                   Más info
                                </a>

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Elemento - Imagen -->
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="carousel-overlay"></div> <!-- Nuevo overlay -->
                    <img src="{{ media_url($slider->imagen) }}" 
                         class="d-block w-100 carousel-imagen" 
                         alt="{{ $slider->titulo }}"
                         style="height: 700px; object-fit: cover;">
                    
                    <!-- Overlay para el contenido -->
                    <div class="carousel-caption d-flex align-items-end align-items-md-center justify-content-start h-100 pb-5 pb-md-0">
                        <div class="aiq-container text-start">
                            <div class="col-md-6">

                                <div class="carousel-title text-white ">
                                    {{ $slider->titulo }}
                                </div>
                                <div class="carousel-subtitle text-white mb-sm-5 mb-4 mt-2">
                                    {!! $slider->descripcion !!}
                                </div>
                                <a href="{{ route('contacto') }}" class="btn-inicio">
                                   Más info
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

  
</div>
<style>
.banner-marcas {
    gap: 18px;
}

.banner-textos {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 6px;
}

.banner-amapcet-text {
    font-size: 26px;
    font-weight: 500;
    color: #111;
    line-height: 1.2;
}
.banner-amapcet-text-bold {
    font-size: 26px;
    font-weight: 700;
    color: #111;
    line-height: 1.2;
}

.banner-logos {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

.banner-amapcet-logo {
    max-height: 120px;
    width: auto;
    object-fit: contain;
}

.banner-liad-logo {
    max-height: 100px;
    width: auto;
    object-fit: contain;
}

@media (max-width: 768px) {
    .banner-marcas {
        gap: 12px;
    }

    .banner-textos {
        gap: 4px;
    }

    .banner-amapcet-text {
        font-size: 16px;
    }

    .banner-amapcet-logo {
        max-height: 40px;
    }

    .banner-liad-logo {
        max-height: 52px;
    }

    .banner-logos {
        gap: 12px;
    }
}
</style>

@php
    $bannerDos = $inicio->banner_dos ?: null;
    $homeProductCards = [
        [
            'url' => route('productos'),
            'title' => $inicio->titulouno ?: 'Masterbatches',
            'image' => $inicio->imagenuna ?: 'inicio/1zihb6RFXXRSzktaVyhu6ozYyrimStcc4r19NF89.png',
        ],
        [
            'url' => route('bobinas'),
            'title' => $inicio->titulodos ?: 'Bobinas y láminas',
            'image' => $inicio->imagendos ?: 'inicio/LBXfCdr6O4xMX6vQxtWPf2lOAyJp5dSJ0filw0V3.png',
        ],
        [
            'url' => route('termoformados'),
            'title' => $inicio->titulotres ?: 'Termoformados',
            'image' => $inicio->imagentres ?: 'inicio/wxb2YUOel4VrAixUGxP0NpuROzkxer6TWSiDqVtK.png',
        ],
    ];
@endphp

<div class="py-4" style="background-color:#F5F5F5;">
    <div class="aiq-container">
        <div class="d-flex flex-column justify-content-center align-items-center banner-marcas">
            
            <div class="banner-textos">
                <span class="banner-amapcet-text">
                    {{ $inicio->titulo_banner }}
                </span>

                <span class="banner-amapcet-text-bold mt-3 font-bold">
                    {{ $inicio->descripcion_banner }}
                </span>
            </div>

            <div class="banner-logos mt-4">
                <img 
                    src="{{ media_url($inicio->banner) }}" 
                    alt="Ampacet"
                    class="banner-amapcet-logo"
                >

                @if($bannerDos)
                <a href="https://liadsmart.com/es/" target="_blank" rel="noopener noreferrer">
                    <img 
                        src="{{ media_url($bannerDos) }}" 
                        alt="Liad Smart"
                        class="banner-liad-logo"
                    >
                </a>
                @endif
            </div>

        </div>
    </div>
</div>

<div class="aiq-container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
            <div class="d-flex flex-column" >
           
            <h1 class="titulo-secciones">Productos</h1>
            </div>

            
            </div>
    <div class="row mt-4">
        @foreach($homeProductCards as $card)
        <div class="col-12 col-md-4 mb-4">
            <a href="{{ $card['url'] }}" class="text-decoration-none">
                
                <div class="categoria-card">

                    <div class="categoria-card__img"
                         style="background-image:url('{{ media_url($card['image']) }}');">
                    </div>

                    <div class="categoria-card__content">
                        <div class="categoria-card__title">
                            {{ $card['title'] }}
                        </div>

                        <div class="categoria-card__arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                 viewBox="0 0 24 24" fill="none"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>

                </div>

            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- =========================
     CONTENIDO INICIO
========================= --}}
<section style="background-color:#0C58A1;" class="my-5">
    <div class="container-fluid p-0">
        <div class="row g-0">

            {{-- IMAGEN --}}
            <div
                class="col-md-6 inicio-imagen"
                style="background-image:url('{{ media_url($inicio->imagen) }}');">
            </div>

            {{-- TEXTO --}}
            <div class="col-md-6 d-flex align-items-center">
                <div class="p-5 inicio-texto" >

                    <h3 class="titulo-inicio">
                        {{ $inicio->titulo }}
                    </h3>

                    <div class="descripcion-inicio my-4 pe-5">
                        {!! $inicio->descripcion !!}
                    </div>

                    <a href="{{ route('empresa') }}" class="btn-secondary">
                        Más info
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

<style>
  .novedades-carousel:not(.slick-initialized) {
    display: flex;
    flex-wrap: wrap;
    margin-inline: -0.5rem;
  }

  .novedades-carousel:not(.slick-initialized) .novedades-slide {
    width: 33.3333%;
    padding-inline: 0.5rem;
  }

  .novedades-carousel .slick-track {
    display: flex;
  }

  .novedades-carousel .slick-slide {
    height: inherit;
  }

  .novedades-carousel .slick-slide > div {
    height: 100%;
  }

  .novedades-carousel .blog-card {
    height: 100%;
  }

  .novedades-carousel .slick-dots {
    bottom: -36px;
  }

  .novedades-carousel .slick-dots li button:before {
    color: #0C58A1;
    opacity: 0.35;
  }

  .novedades-carousel .slick-dots li.slick-active button:before {
    color: #0C58A1;
    opacity: 1;
  }

  @media (max-width: 991.98px) {
    .novedades-carousel:not(.slick-initialized) .novedades-slide {
      width: 50%;
    }
  }

  @media (max-width: 767.98px) {
    .novedades-carousel:not(.slick-initialized) .novedades-slide {
      width: 100%;
    }
  }
</style>

{{-- Blog / Novedades --}}

<div class="bg__novedades">
  <div class="aiq-container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
      <div class="d-flex flex-column">
        <h1 class="titulo-secciones">Novedades</h1>
      </div>

      <a href="{{ route('novedades') }}" class="btn-presupuesto px-4">Ver más</a>
    </div>

    <div class="novedades-carousel mt-5">
      @foreach ($novedades as $index => $novedad)
        <div class="novedades-slide px-2"
             data-aos="fade-up"
             data-aos-delay="{{ $index * 150 }}"
             data-aos-duration="800">

          <div class="card blog-card h-100 mb-4">
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





@push('scripts')

<script>
  $(document).ready(function(){
    if ($.fn.slick) {
      $('.novedades-carousel').slick({
        dots: true,
        arrows: false,
        infinite: {{ $novedades->count() > 3 ? 'true' : 'false' }},
        speed: 800,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: {{ $novedades->count() > 3 ? 'true' : 'false' }},
        autoplaySpeed: 3000,
        adaptiveHeight: false,
        cssEase: 'ease',
        responsive: [
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 2
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      });
    }
  });
</script>

@endpush

@endsection






