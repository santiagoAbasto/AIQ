@extends('admin.layouts.master')

@section('content')
<div class="d-flex justify-content-end">
    <a href="{{ route('admin.slider.create', ['seccion' => $seccion]) }}" class="btn btn-success">
        Nuevo Slider
    </a>
</div>

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('danger'))
    <div class="alert alert-danger">
        {{ session()->get('danger') }}
    </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th scope="col">Orden</th>
            <th scope="col">Titulo</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Imagen</th>
            <th scope="col">Accion</th>
        </tr>
    </thead>
    <tbody>
        @foreach($slider as $s)
        <tr>
            <td>{{ $s->orden }}</td>
            <td>{{ $s->titulo }}</td>
            <td>{!! $s->descripcion !!}</td>
            <td>
                @if (Str::contains($s->imagen, ['.mp4', '.mov', '.avi']))
                <!-- Verificar si es video -->
                <video width="200" height="120" controls>
                    <source src="{{ media_url($s->imagen) }}" type="video/mp4">
                    Tu navegador no soporta video HTML5.
                </video>
                @else
                <!-- Si no es video, es una imagen -->
                <img src="{{ media_url($s->imagen) }}" class="img-thumbnail w-50">
                @endif
            </td>
            <td>
                <a class="btn btn-warning" href="{{ route('admin.slider.edit', [$seccion, 'id' => $s->id]) }}" role="button">
                    <i class="fas fa-edit"></i>
                </a>

                <form action="{{ route('admin.slider.destroy', ['id' => $s->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-item"><i class="far fa-trash-alt"></i> </button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
