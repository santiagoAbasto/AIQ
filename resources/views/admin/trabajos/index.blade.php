{{-- filepath: c:\laragon\www\sinkevicius\resources\views\admin\trabajos\index.blade.php --}}
@extends('admin.layouts.master')

@section('content')

<a href="{{ route('admin.trabajos.create') }}" class="btn btn-success mb-5">Nuevo Trabajo</a>

{{-- ...existing code... --}}
@if(session()->has('danger'))
    <div class="alert alert-danger">
        {{ session()->get('danger') }}
    </div>
@endif
<table class="table">
    <thead>
        <tr>
            <th>Orden</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trabajos as $equipo)
        <tr>
            <td>{{ $equipo->orden }}</td>
            <td>{{ $equipo->titulo }}</td>
            <td>
                <a class="btn btn-warning" href="{{ route('admin.trabajos.edit', ['id' => $equipo->id]) }}" role="button"><i class="fas fa-edit"></i></a>
                <form action="{{ route('admin.trabajos.destroy', ['id' => $equipo->id]) }}" method="POST" style="display: inline;">
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