{{-- filepath: c:\laragon\www\sinkevicius\resources\views\admin\bobinas\index.blade.php --}}
@extends('admin.layouts.master')

@section('content')

<a href="{{ route('admin.bobinas.create') }}" class="btn btn-success mb-5">Nuevo bobina</a>

{{-- ...existing code... --}}
@if(session()->has('danger'))
    <div class="alert alert-danger">
        {{ session()->get('danger') }}
    </div>
@endif
{{-- success --}}
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
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
        @foreach ($bobinas as $bobina)
        <tr>
            <td>{{ $bobina->orden }}</td>
            <td>{{ $bobina->titulo }}</td>
            <td>
                <a class="btn btn-warning" href="{{ route('admin.bobinas.edit', ['id' => $bobina->id]) }}" role="button"><i class="fas fa-edit"></i></a>
                <form action="{{ route('admin.bobinas.destroy', ['id' => $bobina->id]) }}" method="POST" style="display: inline;">
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