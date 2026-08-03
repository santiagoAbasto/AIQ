@extends('layouts.app')
@section('title', 'Presupuestos')
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
                    Solicitud de Presupuesto
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
        <form id="presupuesto-form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type='hidden' name='g-recaptcha-response' id='g-recaptcha-response'>

            <!-- Datos de contacto -->
            <h2 class="mb-4">{{__('datos de contacto')}}</h2>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">{{__('nombre y apellido')}}*</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">{{__('email')}}*</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">{{__('telefono')}}*</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="company" class="form-label">{{__('empresa')}}*</label>
                    <input type="text" class="form-control" id="company" name="company">
                </div>
            </div>

            <!-- Consulta -->
            <h2 class="mb-4 mt-5">{{__('consultar')}}</h2>
            
       
            
            <div class="row mb-4">
            
            <div class="col-md-6 mb-3">
            <label for="file" class="form-label">Adjuntar archivo</label>
            <div class="custom-file-input">
                <input type="file" id="file" name="file" required>
                <span class="file-text">Seleccionar archivo</span>
                <i class="fas fa-arrow-down file-icon"></i>
            </div>
            </div>

            <div class="col-md-6">
                <label for="message" class="form-label">{{__('aclaraciones / observaciones')}}</label>
                <textarea class="form-control" id="message" name="message" rows="4"></textarea>
            </div>

            </div>
            
         

            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <span class="text-muted">*Datos obligatorios</span>
                    <button type="submit" class="btn btn__principal px-4 py-2" id="enviarBtn">{{__('solicitar presupuesto')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Asegurarse de que jQuery esté cargado -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
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

@endsection
