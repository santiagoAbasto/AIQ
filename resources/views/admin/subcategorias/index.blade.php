@extends('admin.layouts.master')

@section('content')

<a href="{{ route('admin.subcategorias.create') }}" class="btn btn-success mb-5">Nueva Subcategoría</a>

@if(session()->has('success'))
    <div class="alert alert-success">{{ session()->get('success') }}</div>
@endif
@if(session()->has('danger'))
    <div class="alert alert-danger">{{ session()->get('danger') }}</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Orden</th>
     
            <th>Título</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($subcategorias as $sub)
        <tr>
            <td>{{ $sub->orden }}</td>
     
            <td>{{ $sub->titulo }}</td>
            <td>
                <a class="btn btn-warning" href="{{ route('admin.subcategorias.edit', ['id' => $sub->id]) }}" role="button"><i class="fas fa-edit"></i></a>
                <form action="{{ route('admin.subcategorias.destroy', ['id' => $sub->id]) }}" method="POST" style="display: inline;">
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
