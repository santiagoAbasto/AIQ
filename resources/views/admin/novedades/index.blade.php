@extends('admin.layouts.master')

@section('content')

<a href="{{ route('admin.novedades.create') }}" class="btn btn-success mb-5">Nueva Novedad</a>

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
            <th>Categoria</th>
            <th>Titulo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($novedades as $novedad)
            <tr>
                <td>{{ $novedad->orden }}</td>
                <td>{{ $novedad->categoria }}</td>

                <td>{{ $novedad->titulo }}</td>
               
                <td>
                    <a class="btn btn-warning" href="{{ route('admin.novedades.edit', ['id' => $novedad->id]) }}" role="button"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.novedades.destroy', ['id' => $novedad->id]) }}" method="POST" style="display: inline;">
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
