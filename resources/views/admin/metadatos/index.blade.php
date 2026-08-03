@extends('admin.layouts.master')

@section('content')
  
        <h1>Metadatos</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Keyword</th>
                    <th>Descripcion</th>
                    <th>Seccion</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metadata as $meta)
                <tr>
                    <td>{{ $meta->id }}</td>
                    <td>{{ $meta->keyword }}</td>
                    <td>{{ $meta->description }}</td>
                    <td>{{ $meta->section }}</td>
                    <td>
                        <a href="{{ route('admin.metadatos.edit', $meta->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                        <!-- Add delete button form if needed -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
 
@endsection
