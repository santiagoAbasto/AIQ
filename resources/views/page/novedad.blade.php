@extends('layouts.app')
@section('title', $novedad->titulo)
@section('content')

<div class="bg__breadcrumb">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb custom-breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="{{ route('index') }}">Inicio</a>
        </li>
        @if($novedad)
        <li class="breadcrumb-item " aria-current="page">
            <a href="{{ route('novedades') }}">Novedades</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">{{ $novedad->titulo }}</li>
        @else
        <li class="breadcrumb-item active" aria-current="page">Novedades</li>
        @endif
      </ol>
    </nav>
  </div>
</div>

<div class="container my-5" style="padding-bottom: 150px" >
    <div class="row justify-content-center">
        <div class="col-md-6" >
            @php
                // Decodificar la galería de forma segura
                $galeria_items = $novedad->galeria ? json_decode($novedad->galeria) : [];
            @endphp

            {{-- Verificar si hay imágenes en la galería --}}
            @if(is_array($galeria_items) && count($galeria_items) > 0)
                {{-- IMPLEMENTACIÓN FOTORAMA --}}
                <div class="fotorama"
                     {{-- data-nav="thumbs" --}}
                     data-allowfullscreen="true"
                     data-autoplay="true"
                     data-transition="crossfade"
                     data-width="100%"
                     data-height="500"
                     data-ratio="16/9"
                     data-fit="cover">

                    {{-- Imágenes de la galería --}}
                    @foreach($galeria_items as $index => $imagen)
                        <img src="{{ media_url($imagen) }}" class="w-100" >
                    @endforeach
                </div>
            @elseif($novedad->imagen)
                {{-- Mostrar imagen principal si no hay galería --}}
                <img src="{{ media_url($novedad->imagen) }}" class="img-fluid" alt="{{ $novedad->titulo }}" style="width: 100%; height: 500px; object-fit: cover;" data-aos="zoom-in" data-aos-delay="300">
            @else
                {{-- Mensaje si no hay ni galería ni imagen principal --}}
                 <p class="text-center">No hay imágenes disponibles.</p>
            @endif
        </div>
        <div class="col-md-6" >
            <h3 class="titulo-empresa" >{{ $novedad->titulo }}</h3>
            <span class="descripcion-empresa" >{!! $novedad->descripcion !!}</span>
        </div>
    </div>
</div>

@endsection