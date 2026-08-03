@extends('layouts.app')
@section('title', 'Termoformados')
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
                    Termoformados
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

<div class="py-4" style="background-color:#F5F5F5;">
    <div class="container">
        <div class="row justify-content-center align-items-center text-left">
            <div class="col-md-12 col-12 text-md-left text-left mb-3 mb-md-0">
                <span class="">
                    {!! $termoformado->descripcion !!}
                </span>
            </div>

          
        </div>
    </div>
</div>


<style>


.form-label-custom {
    color: #2b2b2b;
    font-size: 14px;
    font-weight: 400;
    margin-bottom: 8px;
    display: inline-block;
}

.form-control-custom {
    height: 40px;
    border: 1px solid #d9d9d9;
    border-radius: 6px;
    background: #fff;
    box-shadow: none;
    font-size: 14px;
    color: #333;
}

.form-control-custom:focus {
    border-color: #c9c9c9;
    box-shadow: none;
    background: #f5f5f5;
}

.textarea-custom {
    height: 110px;
    min-height: 84px;
    resize: none;
    padding-top: 10px;
}

.formulario-mensaje {
    margin-top: 138px;
}

.formulario-footer {
    margin-top: 34px;
}

.campos-obligatorios {
    font-size: 14px;
    color: #4a4a4a;
}

.btn-enviar {
    background-color: #ff1623;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 12px 22px;
    font-size: 14px;
    line-height: 1;
    transition: 0.2s ease;
}

.btn-enviar:hover {
    background-color: #e0111d;
    color: #fff;
}

input[type="file"].form-control-custom {
    padding-top: 6px;
    padding-bottom: 6px;
}

input[type="file"].form-control-custom::file-selector-button {
    display: none;
}

@media (max-width: 767.98px) {
    .formulario-mensaje {
        margin-top: 0;
    }

    .formulario-footer {
        margin-top: 20px;
        flex-direction: column;
        align-items: flex-start !important;
        gap: 16px;
    }

    .btn-enviar {
        width: 100%;
    }
}

.termoformados-gallery {
    margin-top: 3rem;
}

.termoformados-gallery:not(.slick-initialized) {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 22px;
}

.termoformados-gallery__item {
    padding: 0 10px;
}

.termoformados-gallery__image {
    width: 100%;
    aspect-ratio: 1.35 / 1;
    object-fit: cover;
    border-radius: 10px;
    display: block;
}

.termoformados-gallery .slick-list {
    margin: 0 -10px;
}

.termoformados-gallery .slick-dots {
    bottom: -38px;
}

.termoformados-gallery .slick-dots li button:before {
    font-size: 10px;
    color: #ff1623;
    opacity: 0.35;
}

.termoformados-gallery .slick-dots li.slick-active button:before {
    color: #ff1623;
    opacity: 1;
}

.termoformados-gallery .slick-prev,
.termoformados-gallery .slick-next {
    z-index: 2;
    width: 40px;
    height: 40px;
}

.termoformados-gallery .slick-prev {
    left: -8px;
}

.termoformados-gallery .slick-next {
    right: -8px;
}

.termoformados-gallery .slick-prev:before,
.termoformados-gallery .slick-next:before {
    font-size: 40px;
    color: #ff1623;
}

@media (max-width: 767.98px) {
    .termoformados-gallery:not(.slick-initialized) {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .termoformados-gallery__image {
        aspect-ratio: 1.2 / 1;
    }

    .termoformados-gallery .slick-prev {
        left: 4px;
    }

    .termoformados-gallery .slick-next {
        right: 4px;
    }
}
</style>





<div class="container my-5">
    <div class="row">

        {{-- galeria json --}}
@php
    $galeria = $termoformado->galeria ?? '';
    $imagenes = [];

    if (is_string($galeria) && !empty($galeria)) {
        $jsonDecoded = json_decode($galeria, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($jsonDecoded)) {
            $imagenes = $jsonDecoded;
        } else {
            $imagenes = explode(',', $galeria);
        }
    } elseif (is_array($galeria)) {
        $imagenes = $galeria;
    }

    $imagenes = array_filter($imagenes);
@endphp

@if(count($imagenes))
    <div class="container mb-5">
        <div class="termoformados-gallery">
            @foreach($imagenes as $item)
                @php
                    $imagen = is_array($item) ? ($item['imagen'] ?? null) : $item;
                @endphp

                @if(!empty($imagen))
                    <div class="termoformados-gallery__item">
                        <img 
                            src="{{ media_url($imagen) }}" 
                            alt="Galería" 
                            class="termoformados-gallery__image"
                        >
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
<form id="presupuesto-form" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

    <div class="row g-4">
        {{-- Columna izquierda --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label form-label-custom">{{ __('Nombre y apellido') }}*</label>
                <input type="text" class="form-control form-control-custom" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label form-label-custom">{{ __('Teléfono') }}*</label>
                <input type="text" class="form-control form-control-custom" id="phone" name="phone" required>
            </div>

            <div class="mb-3">
                <label for="uso" class="form-label form-label-custom">{{ __('Uso') }}*</label>
                <input type="text" class="form-control form-control-custom" id="uso" name="uso" required>
            </div>

            <div class="mb-3">
                <label for="medidas" class="form-label form-label-custom">{{ __('Medidas aproximadas') }}*</label>
                <input type="text" class="form-control form-control-custom" id="medidas" name="medidas" required>
            </div>

            <div class="mb-0">
                <label for="archivo" class="form-label form-label-custom">{{ __('Adjuntar archivo') }}</label>
                <input type="file" class="form-control form-control-custom" id="archivo" name="archivo">
            </div>
        </div>

        {{-- Columna derecha --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email" class="form-label form-label-custom">{{ __('Email') }}*</label>
                <input type="email" class="form-control form-control-custom" id="email" name="email" required>
            </div>

            <div class="mb-3 formulario-mensaje">
                <label for="message" class="form-label form-label-custom">{{ __('Mensaje') }}</label>
                <textarea class="form-control form-control-custom textarea-custom" id="message" name="message"></textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center formulario-footer">
                <span class="campos-obligatorios">*Campos Obligatorios</span>

                <button type="submit" class="btn btn-enviar" id="enviarBtn">
                    {{ __('Enviar mensaje') }}
                </button>
            </div>
        </div>
    </div>
</form>



    </div>
</div>
@endsection

@push('scripts')

<script>
  $(document).ready(function(){
        if ($.fn.slick) { 
            $('.termoformados-gallery').slick({
                dots: false,
                arrows: true,
                infinite: true,
                speed: 700,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2500,
                adaptiveHeight: false,
                variableWidth: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            arrows: false,
                        }
                    }
                ]
            });
        }
  });
</script>
<script>
  $(document).ready(function() {
      $('#presupuesto-form').on('submit', function(e) {
          e.preventDefault();
          
          // Mostrar indicador de carga
          $('#enviarBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enviando...');
          
          // Reactivar reCAPTCHA (opcional)
       
          grecaptcha.ready(function() {
              grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'submit'}).then(function(token) {
                  document.getElementById('g-recaptcha-response').value = token;
          
                  
                  var formData = new FormData(document.getElementById('presupuesto-form'));
                  
                  $.ajax({
                      type: 'POST',
                      url: "{{ route('presupuesto.send') }}",
                      data: formData,
                      processData: false,
                      contentType: false,
                      headers: {
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      success: function(response) {
                          Swal.fire({
                              icon: 'success',
                              title: '¡Presupuesto Enviado!',
                              text: response.message,
                          });
                          $('#presupuesto-form')[0].reset();
                      },
                      error: function(response) {
                          var errors = response.responseJSON?.errors;
                          var errorsHtml = '<ul>';
                          
                          if (errors) {
                              $.each(errors, function(key, value) {
                                  errorsHtml += '<li>' + value[0] + '</li>';
                              });
                          } else {
                              errorsHtml += '<li>Error al enviar el presupuesto</li>';
                          }
                          errorsHtml += '</ul>';
          
                          Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              html: 'Hubo un error al enviar el presupuesto:<br>' + errorsHtml,
                          });
                      },
                      complete: function() {
                          // Restablecer el botón cuando finalice la solicitud
                          $('#enviarBtn').prop('disabled', false).text('Solicitar presupuesto');
                      }
                  });
                 
              });
          });
         
      });
  });
</script>

@endpush
