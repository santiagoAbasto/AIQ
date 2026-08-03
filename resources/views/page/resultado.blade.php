@extends('layouts.app')
@section('title', $query ? 'Resultados para "' . $query . '"' : 'Búsqueda')
@section('content')

<style>
    .search-results-page {
        padding-top: 48px;
        padding-bottom: 72px;
    }

    .search-results-title {
        color: #212529;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 16px;
    }

    .search-results-form {
        max-width: 400px;
    }

    .search-results-form .form-control {
        min-height: 46px;
        border-color: #d8dce2;
        border-radius: 8px 0 0 8px;
        font-size: 15px;
    }

    .search-results-form .btn {
        min-height: 46px;
        border-radius: 0 8px 8px 0;
        background-color: #b90514;
        border-color: #b90514;
        font-weight: 600;
        padding-left: 22px;
        padding-right: 22px;
    }

    .search-results-form .btn:hover {
        background-color: #950410;
        border-color: #950410;
    }

    .search-results-page .producto-card__img-wrapper.is-missing {
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-results-page .producto-card__img-wrapper.is-missing::after {
        content: "Imagen no disponible";
        color: #7a8088;
        font-size: 14px;
        font-weight: 500;
    }
</style>

<div class="container search-results-page">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="search-results-title">
                @if($query)
                    {{ $productos->count() }} resultado(s) para <em>"{{ $query }}"</em>
                @else
                    Todos los productos
                @endif
            </h1>
            <form action="{{ route('buscador') }}" method="GET" class="search-results-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ $query }}">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @if ($productos->isEmpty())
            <div class="col-12">
                <p class="text-muted">No se encontraron productos para "{{ $query }}".</p>
                <a href="{{ route('productos') }}" class="btn btn-outline-primary mt-2">Ver todos los productos</a>
            </div>
        @else
          @foreach($productos as $producto)
      <div class="col-12 col-md-4 mb-4">
        <a href="{{ route('contacto') }}" class="text-decoration-none">
          <div class="producto-card h-100">

            <div class="producto-card__img-wrapper {{ empty($producto->imagen) ? 'is-missing' : '' }}">
              @if(!empty($producto->imagen))
                <img
                  src="{{ media_url($producto->imagen) }}"
                  alt="{{ $producto->titulo }}"
                  class="producto-card__img"
                  onerror="this.parentElement.classList.add('is-missing'); this.remove();"
                >
              @endif
            </div>

            <div class="producto-card__body">
              <div class="producto-card__categoria">
               Masterbatches - {{ $producto->relaciones->first()?->categoria?->titulo ?? '—' }} 
              </div>

              <div class="producto-card__titulo">
                {{ $producto->titulo }}
              </div>

              <div class="producto-card__texto">
                {!! Str::limit(strip_tags($producto->descripcion), 100) !!}
              </div>

             <div class="producto-card__cta">
    <div class="producto-card__feature">
        @if($producto->caracteristica)
            <img src="{{ media_url($producto->caracteristica->imagen) }}" alt="" style="width:24px; height:24px; margin-right:8px;">
            <span class="producto-card__caracteristica">{{ $producto->caracteristica->titulo }}</span>
        @else
            <span>&nbsp;</span>
        @endif
    </div>

    <a href="{{ route('contacto') }}" class="btn-ver-mas">Consultar</a>
</div>
            </div>

          </div>
        </a>
      </div>
    @endforeach
        @endif
    </div>
</div>

@endsection
