@extends('layouts.app')
@section('title', 'Categorías')
@section('content')

<div class="bg__breadcrumb">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb custom-breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="{{ route('index') }}">Inicio</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Categorias</li>
      </ol>
    </nav>
  </div>
</div>

<div class="container my-5">
    <div class="row mt-4">
        @foreach($categorias as $categoria)
                <div class="col-12 col-md-6 col-sm-12 mb-4">
                    <div class="categoria">
                            <div class="categoria__nombre">
                              <a href="{{ route('productos', $categoria->slug) }}">{{ $categoria->titulo }}</a>
                            </div>
                            <a href="{{ route('productos', $categoria->slug) }}" class="subcategoria__foto-container">
                                <div class="subcategoria__foto" style="background: url('{{ media_url($categoria->imagen) }}');
                                    background-size: contain;
                                    background-position: right;
                                    background-repeat: no-repeat;">
                                    <!-- La imagen se maneja como fondo -->
                                </div>
                            </a>
                      </div>
                </div>
        @endforeach
    </div>
</div>
@endsection