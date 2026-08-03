@extends('admin.layouts.master')

@section('content')

<a href="{{ route('admin.productos.create') }}" class="btn btn-success mb-5">Nuevo Producto</a>

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
            <th>Orden</th>
            <th>categorias-subcategorias</th>
            <th>Titulo</th>
            {{-- <th>qr</th> --}}
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $prod)
    <tr>
        <td>{{ $prod->orden }}</td>
        <td>
            @forelse($prod->relaciones as $rel)
                <span class="">
                    {{ $rel->categoria?->titulo ?? '—' }}
                    @if($rel->subcategoria)
                        / {{ $rel->subcategoria->titulo }}
                    @endif
                </span>
            @empty
                <span class="text-muted">Sin relaciones</span>
            @endforelse
        </td>
        <td>{{ $prod->titulo }}</td>
    
         
       
        <td>
            {{-- <a class="btn btn-primary" href="{{ route('admin.productos.index_colores', $prod->id) }}" role="button">Colores</a> --}}
            <a class="btn btn-warning" href="{{ route('admin.productos.edit', ['id' => $prod->id]) }}" role="button"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.productos.destroy', ['id' => $prod->id]) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger delete-item"><i class="far fa-trash-alt"></i></button>
            </form>
        </td>
    </tr>
@endforeach

    </tbody>
</table>

@endsection
