@extends('admin.layouts.master')

@section('content')

<a href="{{ route('admin.caracteristicas.create') }}" class="btn btn-success mb-5">Nueva Característica</a>

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
            <th>Titulo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($caracteristicas as $caracteristica)
    <tr>
        <td>{{ $caracteristica->orden }}</td>
        <td>{{ $caracteristica->titulo }}</td>
        <td>   
        <a class="btn btn-warning" href="{{ route('admin.caracteristicas.edit', ['id' => $caracteristica->id]) }}" role="button"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.caracteristicas.destroy', ['id' => $caracteristica->id]) }}" method="POST" style="display: inline;">
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
