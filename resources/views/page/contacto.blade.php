@extends('layouts.app')
@section('title', 'Contacto')
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
                    Contacto
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
/* ==========================
   CONTACTO (como la captura)
   ========================== */

/* Columna izquierda */
.info-contact{
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.item-contact{
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.icon-contact{
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  margin-top: 2px;
}

.item-contact a{
    font-family: 'Plus Jakarta Sans';
    font-weight: 400;
    font-style: 'Regular';
    font-size: 14px;
    line-height: 150%;
    letter-spacing: 0%;

  color: #000;
  text-decoration: none;
}

.item-contact a:hover{
  color: #FB0D1B;
  text-decoration: underline;
}

/* Títulos / textos (si querés que queden más prolijos) */
.titulo__secciones{
  margin: 6px 0 8px;
}

.subtitulo__secciones{
  font-family: 'Plus Jakarta Sans';
    font-weight: 400;
    font-style: 'Regular';
    font-size: 16px;
    color: #151414;
    line-height: 150%;
    letter-spacing: 0%;

}

/* ==========================
   FORM
   ========================== */
#contact-form .form-label{
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 400;
    font-style: 'Regular';
    font-size: 16px;
    line-height: 150%;
    letter-spacing: 0%;

}

#contact-form .form-control{
  border: 1px solid #d1d5db;
  border-radius: 3px;
  padding: 10px 12px;
  font-size: 14px;
  box-sizing: border-box;
  background: #fff;
}

#contact-form input.form-control{
  height: 42px;
}

#contact-form textarea.form-control{
  min-height: 170px; /* parecido a la captura */
  resize: none;
}

#contact-form .form-control:focus{
  border-color: #FB0D1B;
  box-shadow: 0 0 0 2px rgba(15,53,100,.12);
  outline: none;
}

/* Feedback de validación */
#contact-form .invalid-feedback{
  font-size: 12px;
}

/* ==========================
   ÚLTIMA FILA: Mensaje full + pie + botón a la derecha
   (sin tocar tu HTML)
   ========================== */
@media (min-width: 768px){
  /* sacale el mt-5 al form en desktop */
  .col-md-8.mt-5{ margin-top: 0 !important; }

  /* Esta es la última .row del form (la que tiene textarea + botón) */
  #contact-form > .row:last-of-type{
    flex-direction: column;
  }

  /* textarea a 100% */
  #contact-form > .row:last-of-type > .col-md-6:first-child{
    flex: 0 0 100%;
    max-width: 100%;
  }

  /* bloque de acciones abajo, en una fila: texto izq + botón der */
  #contact-form > .row:last-of-type > .col-md-6:last-child{
    flex: 0 0 100%;
    max-width: 100%;
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 16px;
    padding-top: 10px;
  }

  /* tu texto "*Datos obligatorios" */
  #contact-form > .row:last-of-type .form-text{
    margin: 0 !important;
    color: #6b7280;
    font-size: 12px;
    text-align: left !important;
  }
}

/* ==========================
   BOTÓN (pill azul como la captura)
   ========================== */
.carousel-btn{
  background: #FB0D1B;
  color: #fff;
  border: 0;
    border-radius: 8px;
  padding: 10px 20px;
  font-weight: 600;
  font-size: 13px;
  line-height: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: background .15s ease, transform .05s ease;
  box-shadow: 0 8px 18px rgba(0,0,0,.10);
}

/* en desktop que NO sea full width (aunque tenga w-100) */
@media (min-width: 768px){
  .carousel-btn.w-100{ width: auto !important; min-width: 170px; }
}

.carousel-btn:hover{
  background: #0b2b52;
  color: #fff;
}

.carousel-btn:active{
  transform: translateY(1px);
}




</style>

<div class="container my-5">
  <div class="row">
   <div class="col-md-4">
  <div class="info-contact">

    <p class="subtitulo__secciones text-left mb-4">
      Si desea realizar una solicitud de presupuesto complete el siguiente formulario y nos pondremos en contacto a la brevedad
    </p>

    <div class="item-contact mb-3">
    <svg xmlns="http://www.w3.org/2000/svg"
     width="24" height="24" viewBox="0 0 24 24"
     fill="none" style="color:#FB0D1B">
  <path d="M12 22s7-7.2 7-12a7 7 0 1 0-14 0c0 4.8 7 12 7 12z"
        stroke="currentColor" stroke-width="2"/>
  <circle cx="12" cy="10" r="2.5"
          stroke="currentColor" stroke-width="2"/>
</svg>

      <div>
        <a href="{{$contacto->enlace}}" target="_blank" class="">
          {{$contacto->direccion}}
        </a>
      </div>
    </div>

    <div class="item-contact mb-3">
   <svg xmlns="http://www.w3.org/2000/svg"
     width="24" height="24" viewBox="0 0 24 24"
     fill="none" style="color:#FB0D1B">
  <path d="M22 16.9v3a2 2 0 0 1-2.2 2
           c-9.2-.6-16.9-8.3-17.5-17.5
           A2 2 0 0 1 4.3 2h3
           a2 2 0 0 1 2 1.7
           c.1 1 .3 2 .6 3
           a2 2 0 0 1-.5 2.1L8 10
           a16 16 0 0 0 6 6
           l1.2-1.4a2 2 0 0 1 2.1-.5
           c1 .3 2 .5 3 .6
           a2 2 0 0 1 1.7 2z"
        stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round"/>
</svg>

      <div>
        <a href="tel:{!!$contacto->telefono!!}" class="">
          {{$contacto->telefono}}
        </a>
      </div>
    </div>

    <div class="item-contact mb-3">
<svg xmlns="http://www.w3.org/2000/svg"
     width="24" height="24" viewBox="0 0 24 24"
     fill="none" style="color:#FB0D1B">
  <rect x="3" y="5" width="18" height="14" rx="2"
        stroke="currentColor" stroke-width="2"/>
  <path d="M3 7l9 6 9-6"
        stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round"/>
</svg>

      <div>
        <a href="mailto:{{$contacto->correo}}" class="">
          {{$contacto->correo}}
        </a>
      </div>
    </div>

     <div class="item-contact">
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5823 11.985C14.3328 11.8608 13.1095 11.2625 12.8817 11.1792C12.6539 11.0967 12.4881 11.0558 12.3215 11.3042C12.1557 11.5508 11.6793 12.1092 11.5344 12.2742C11.3887 12.44 11.2438 12.46 10.9952 12.3367C10.7465 12.2117 9.94429 11.9508 8.99391 11.1075C8.25453 10.4509 7.75464 9.64002 7.60978 9.39169C7.46492 9.14419 7.59387 9.01002 7.71863 8.88669C7.83084 8.77585 7.96732 8.59752 8.09209 8.45335C8.21685 8.30835 8.25788 8.20502 8.34078 8.03919C8.42451 7.87419 8.38265 7.73002 8.31985 7.60585C8.25788 7.48169 7.7605 6.26252 7.55284 5.76669C7.35104 5.28419 7.14589 5.35003 6.99349 5.34169C6.8478 5.33503 6.682 5.33336 6.51621 5.33336C6.35041 5.33336 6.08079 5.39503 5.85303 5.64336C5.62444 5.89086 4.98219 6.49002 4.98219 7.70919C4.98219 8.92752 5.87313 10.105 5.99789 10.2709C6.12266 10.4359 7.75213 12.9375 10.2482 14.01C10.8428 14.265 11.3058 14.4175 11.6667 14.5308C12.2629 14.72 12.8055 14.6933 13.2342 14.6292C13.7115 14.5583 14.7063 14.03 14.9139 13.4517C15.1208 12.8733 15.1207 12.3775 15.0588 12.2742C14.9968 12.1708 14.831 12.1092 14.5815 11.985H14.5823ZM10.0423 18.1542H10.0389C8.55634 18.1544 7.10099 17.7578 5.8254 17.0058L5.52396 16.8275L2.39062 17.6458L3.22712 14.6058L3.03035 14.2942C2.20149 12.9811 1.76286 11.4615 1.76512 9.91085C1.76679 5.36919 5.47958 1.6742 10.0456 1.6742C12.2562 1.6742 14.3345 2.53253 15.897 4.08919C16.6676 4.85301 17.2785 5.76133 17.6941 6.7616C18.1098 7.76188 18.322 8.83425 18.3186 9.91668C18.3169 14.4583 14.6041 18.1542 10.0423 18.1542ZM17.086 2.9067C16.1634 1.98247 15.0657 1.24965 13.8564 0.7507C12.6472 0.251754 11.3505 -0.00339687 10.0414 3.41479e-05C4.55347 3.41479e-05 0.085409 4.44586 0.0837344 9.91002C0.0811914 11.649 0.539563 13.3578 1.4126 14.8642L0 20L5.27861 18.6217C6.73884 19.4134 8.37519 19.8283 10.0381 19.8283H10.0423C15.5302 19.8283 19.9983 15.3825 20 9.91752C20.004 8.61525 19.7485 7.32511 19.2484 6.12172C18.7482 4.91833 18.0132 3.82559 17.086 2.9067Z" fill="#FB0D1B"/>
</svg>

      <div>
        <a href="https://api.whatsapp.com/send?phone={{$contacto->whatsapp}}" target="_blank" class="">
          {{$contacto->whatsapp}}
        </a>
      </div>
    </div> 
  </div>
</div>
 
    <div class="col-md-8 mt-5">
        <form id="contact-form" method="POST">
    @csrf
    <input type='hidden' name='g-recaptcha-response' id='g-recaptcha-response'>
    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" required >
            <div class="invalid-feedback" id="name-error"></div>
        </div>
        <div class="col-md-6 mb-4">
            <label for="surname" class="form-label">Apellido <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="surname" name="surname" required >
            <div class="invalid-feedback" id="surname-error"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" required >
            <div class="invalid-feedback" id="email-error"></div>
        </div>
        <div class="col-md-6 mb-4">
            <label for="phone" class="form-label">Teléfono <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" id="phone" name="phone" required >
            <div class="invalid-feedback" id="phone-error"></div>
        </div>
    </div>
<div class="row">
    <div class="col-md-12 mb-4">
        <label for="message" class="form-label">Mensaje<span class="text-danger">*</span></label>
        <textarea class="form-control" id="message" name="message" rows="5"></textarea>
        <div class="invalid-feedback" id="message-error"></div>
    </div>

    <div class="col-md-12 d-flex justify-content-between align-items-center ">
        <div class="form-text text-end mb-2">
            *Datos obligatorios
        </div>
        <button type="submit" class="btn-inicio mt-auto ">
            Enviar consulta
        </button>
    </div>
</div>

    
  </form>
</div>
  </div>
</div>

<div class="container my-5">
    <div class="row">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3284.542150069324!2d-58.53128612353144!3d-34.590449856798905!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcb7bd278f7057%3A0xf5bf113d7e8dbf78!2sAiq%20S.A.!5e0!3m2!1ses-419!2sar!4v1773860727785!5m2!1ses-419!2sar" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ env("RECAPTCHA_SITE_KEY") }}"></script>
<script>

document.addEventListener('DOMContentLoaded', function () {
    grecaptcha.ready(function () {
        grecaptcha.execute('{{ env("RECAPTCHA_SITE_KEY") }}', { action: 'submit' }).then(function (token) {
            document.getElementById('g-recaptcha-response').value = token;
        });
    });

    // Función para resetear los errores del formulario
    function resetFormErrors() {
        document.querySelectorAll('.invalid-feedback').forEach(elem => {
            elem.textContent = '';
        });
        document.querySelectorAll('.form-control').forEach(elem => {
            elem.classList.remove('is-invalid');
        });
    }

    // Función para mostrar errores en los campos específicos
    function showFieldErrors(errors) {
        if (!errors) return;
        
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`${field}-error`);
            const inputElement = document.getElementById(field);
            
            if (errorElement && inputElement) {
                errorElement.textContent = errors[field][0];
                inputElement.classList.add('is-invalid');
            }
        });
    }

    document.getElementById('contact-form').addEventListener('submit', function (event) {
        event.preventDefault();
        resetFormErrors();

        grecaptcha.execute('{{ env("RECAPTCHA_SITE_KEY") }}', { action: 'submit' }).then(function (token) {
            document.getElementById('g-recaptcha-response').value = token;

            let form = event.target;
            let formData = new FormData(form);

            $.ajax({
                url: '{{ route("contacto.send") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.message) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Consulta enviada!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        form.reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error || 'Error desconocido.',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function (error) {
                    console.error('Error:', error);
                    
                    if (error.responseJSON?.errors) {
                        showFieldErrors(error.responseJSON.errors);
                        
                        // Mensaje general de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en el formulario',
                            text: 'Por favor corrija los errores en el formulario.',
                            showConfirmButton: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al enviar el mensaje. Inténtelo nuevamente.',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        });
    });
});
</script>
@endpush
