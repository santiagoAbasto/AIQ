@extends('admin.layouts.master')

@section('title', 'Importar clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Clientes importados</h3>
        <p class="mb-0">{{ $cliente->name }} · {{ $cliente->email }}</p>
    </div>
    <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Volver</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-end">
            <div class="col-12 col-lg-7">
                <p class="admin-page-kicker">Importación admin</p>
                <h4 class="mb-2">Importar Excel para este cliente</h4>
                <p class="mb-lg-0 text-muted">Columnas admitidas: nombre, email, empresa, teléfono, producto, consulta.</p>
            </div>
            <div class="col-12 col-lg-5">
                <form method="POST" action="{{ route('admin.clientes.imports.store', $cliente) }}" enctype="multipart/form-data" class="d-flex gap-2">
                    @csrf
                    <input class="form-control" type="file" name="archivo" accept=".xlsx,.xls,.csv,.txt" required>
                    <button class="btn btn-primary" type="submit">Importar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Empresa</th>
                <th>Teléfono</th>
                <th>Producto</th>
                <th>Archivo</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($importados as $item)
                <tr>
                    <td>{{ $item->nombre ?: '-' }}</td>
                    <td>{{ $item->email ?: '-' }}</td>
                    <td>{{ $item->empresa ?: '-' }}</td>
                    <td>{{ $item->telefono ?: '-' }}</td>
                    <td>{{ $item->producto ?: '-' }}</td>
                    <td>{{ $item->source_file ?: '-' }}</td>
                    <td>{{ optional($item->imported_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Este cliente todavía no importó registros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $importados->onEachSide(1)->links('admin.partials.pagination') }}
@endsection
