@extends('layouts.app')
@section('title', $producto->titulo)
@section('content')



<style>


</style>

<div class="bg__breadcrumb">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb custom-breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="{{ route('index') }}">Inicio</a>
        </li>
        <li class="breadcrumb-item " aria-current="page">
            <a href="{{ route('productos') }}">Productos</a>
        </li>
        <li class="breadcrumb-item " aria-current="page">
            <a href="{{ route('productos', $categoriaSeleccionada->slug) }}">{{ $categoriaSeleccionada->titulo }}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">{{ $producto->titulo }}</li>
      </ol>
    </nav>
  </div>
</div>

<div class="container my-5">
    <div class="row">
        {{-- SIDEBAR DE CATEGORÍAS --}}
        <div class="col-md-2 mb-4">
            
            
            @foreach($categorias as $cat)
                <a href="{{ route('productos', $cat->slug) }}" 
                   class="sidebar-categoria {{ (isset($categoriaSeleccionada) && $categoriaSeleccionada->id == $cat->id) ? 'active' : '' }}">
                   {{ $cat->titulo }}
                </a>
            @endforeach
        </div>

        {{-- DETALLE DEL PRODUCTO --}}
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-6 ">
                    {{-- Marco de imagen principal con Fotorama --}}
                   <div class="fotorama" 
                    data-arrows="true" 
                    data-click="true" 
                    data-swipe="true" 
                    data-width="100%" 
                    data-height="400px"
                    data-fit="contain" 
                    data-transition="crossfade" 
                    data-nav="thumbs"
                    data-thumbwidth="80"
                    data-thumbheight="80"
                >
                    @php
                    // Mejorar el procesamiento de la galería
                    $galeria = $producto->galeria ?? '';
                    $imagenes = [];
                    
                    // Comprobar si es una cadena JSON y decodificarla
                    if (is_string($galeria) && !empty($galeria)) {
                        $jsonDecoded = json_decode($galeria, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($jsonDecoded)) {
                            $imagenes = $jsonDecoded;
                        } else {
                            // Si no es JSON válido, intentar como CSV
                            $imagenes = explode(',', $galeria);
                        }
                    } elseif (is_array($galeria)) {
                        $imagenes = $galeria;
                    }
                    
                    // Filtrar valores vacíos
                    $imagenes = array_filter($imagenes);
                    @endphp
                    
                    @if(count($imagenes) > 0)
                        @foreach($imagenes as $imagen)
                            @if(Str::endsWith(strtolower($imagen), ['.mp4', '.avi', '.mov', '.webm']))
                                <a href="{{ media_url($imagen) }}" data-video="true">
                                    <video class="fotorama__video" muted playsinline style="width:100%;height:100%;object-fit:contain;">
                                        <source src="{{ media_url($imagen) }}" type="video/{{ pathinfo($imagen, PATHINFO_EXTENSION) }}">
                                    </video>
                                </a>
                            @else
                                <a href="{{ media_url($imagen) }}">
                                    <img src="{{ media_url($imagen) }}" alt="{{ $producto->titulo }}">
                                </a>
                            @endif
                        @endforeach
                    @elseif(!empty($producto->imagen))
                        <a href="{{ media_url($producto->imagen) }}">
                            <img src="{{ media_url($producto->imagen) }}" alt="{{ $producto->titulo }}">
                        </a>
                    @else
                        <a href="{{ asset('images/no-image.png') }}">
                            <img src="{{ asset('images/no-image.png') }}" alt="Sin imagen disponible">
                        </a>
                    @endif
                </div>
                </div>

                <div class="col-md-6 ps-md-4 d-flex flex-column">
                    {{-- Título y Código --}}
                    <h3 class="product-categorie">EQUIPOS DE RAMPA</h3>

                    <h1 class="product-title">{{ $producto->titulo }}</h1>
                   
                    
                    {{-- Descripción --}}
                    <div class="mt-4">
                        
                        <hr class="mt-1 mb-3" style="border-top: 1px solid #dee2e6;">
                        
                        <div class="product-desc">
                             {!! $producto->descripcion !!}
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex gap-3 mt-auto">
                        @if($producto->pdf)
                        <div class="w-50">
                            <a href="{{ media_url($producto->pdf) }}" target="_blank" class="btn-certificado">
                               Ficha técnica   
                            </a>
                        </div>
                        @endif
                        
                        <div class="{{ $producto->pdf ? 'w-50' : 'w-100' }}">
                             <a href="{{ route('presupuesto') }}" class="btn-consultar">
                                Solicitar presupuesto
                            </a>
                        </div>
                    </div>
                </div>
               
                {{-- caracteristcas conexion segurdida --}}
                <div class="col-md-4">
                    @if($producto->caracteristicas)
                    <div class="mt-4">
                        <span class="desc-label">CARACTERÍSTICAS</span>
                        <hr class="mt-1 mb-3" style="border-top: 1px solid #dee2e6;">   
                        {!! $producto->caracteristicas !!}
                    </div>
                    @endif
                </div>
               

            </div>
               
             {{-- Productos Relacionados --}}
            @if($producto->relacionados->count() > 0)
            <div class="row mt-5">
                        <div class="col-12 mb-4">
                            <h3 class="product-title" style="font-size: 24px;">Productos Relacionados</h3>
                            <hr style="border-top: 2px solid #11345A; width: 50px; opacity: 1;">
                        </div>
                        
                        @foreach($producto->relacionados as $relacionado)
                      <div class="col-12 col-md-4 mb-4">
        <a href="{{ route('producto', $relacionado->slug) }}" class="text-decoration-none">
          <div class="producto-card h-100">

            <div class="producto-card__img-wrapper">
              <img
                src="{{ media_url($relacionado->imagen) }}"
                alt="{{ $relacionado->titulo }}"
                class="producto-card__img"
              >
            </div>

            <div class="producto-card__body">
              <div class="producto-card__categoria">
                {{ $relacionado->categoria->titulo ?? 'Pulidoras' }}
              </div>

              <div class="producto-card__titulo">
                {{ $relacionado->titulo }}
              </div>

              <div class="producto-card__cta">
                <span>Ver más</span>

                <svg class="producto-card__arrow" xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M4 12H18" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>
                  <path d="M12 6L18 12L12 18" stroke="currentColor" stroke-width="1"
                        stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

              </div>
            </div>

          </div>
        </a>
      </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

