@extends('admin.layouts.master')

@section('title', 'Mensajes de contacto')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Mensajes de contacto</h1>
    <p class="mb-4">Lista de mensajes recibidos a través del formulario de contacto.</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Mensajes recibidos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Asunto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mensajes as $mensaje)
                            <tr>
                                <td>{{ $mensaje->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $mensaje->name }}</td>
                                <td>{{ $mensaje->email }}</td>
                                <td>{{ $mensaje->phone }}</td>
                                <td>{{ $mensaje->message }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.contactomensaje.show', $mensaje->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <form action="{{ route('admin.contactomensaje.destroy', $mensaje->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este mensaje?')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay mensajes de contacto disponibles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            }
        });
    });
</script>
@endpush
