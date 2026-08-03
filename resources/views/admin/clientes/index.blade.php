@extends('admin.layouts.master')

@section('title', 'Gestión de clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Gestión de Clientes</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.clientes.knowledge') }}" class="btn btn-outline-primary">Base IA PDF</a>
        <a href="{{ route('admin.clientes.ai') }}" class="btn btn-outline-primary">Consultas IA</a>
        <a href="{{ route('admin.clientes.create') }}" class="btn btn-success">Nuevo Cliente</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('danger'))
    <div class="alert alert-danger">{{ session('danger') }}</div>
@endif

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Empresa</th>
                <th>Acceso</th>
                <th>Inactividad</th>
                <th>Registros</th>
                <th>IA</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clientes as $cliente)
                <tr>
                    <td>
                        <strong>{{ $cliente->name }}</strong><br>
                        <small>{{ $cliente->email }}</small>
                    </td>
                    <td>{{ $cliente->company ?: '-' }}</td>
                    <td>
                        @if($cliente->hasValidAccess())
                            <span class="badge bg-success">{{ $cliente->access_status }}</span>
                        @elseif($cliente->is_enabled)
                            <span class="badge bg-danger">{{ $cliente->access_status }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $cliente->access_status }}</span>
                        @endif
                    </td>
                    <td style="min-width: 180px;">
                        @if($cliente->is_enabled)
                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Activo
                            </span>
                            <small class="d-block text-muted mt-1">Contador detenido</small>
                        @elseif($cliente->inactive_since_at)
                            <strong class="d-block text-dark">
                                {{ $cliente->inactive_days }} {{ $cliente->inactive_days === 1 ? 'día' : 'días' }}
                            </strong>
                            @if($cliente->days_until_deletion > 0)
                                <small class="text-warning">
                                    <i class="far fa-clock me-1"></i>
                                    Se elimina en {{ $cliente->days_until_deletion }}
                                    {{ $cliente->days_until_deletion === 1 ? 'día' : 'días' }}
                                </small>
                            @else
                                <small class="text-danger">
                                    <i class="fas fa-triangle-exclamation me-1"></i>
                                    Pendiente de depuración
                                </small>
                            @endif
                        @else
                            <span class="text-muted">Sin iniciar</span>
                        @endif
                    </td>
                    <td>{{ $cliente->imported_clientes_count }}</td>
                    <td>{{ $cliente->ai_requests_count }}</td>
                    <td>
                        <a href="{{ route('admin.clientes.edit', $cliente) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.clientes.imports', $cliente) }}" class="btn btn-info btn-sm"><i class="fas fa-file-excel"></i></a>
                        <form action="{{ route('admin.clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este cliente?')">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay clientes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $clientes->onEachSide(1)->links('admin.partials.pagination') }}
@endsection
