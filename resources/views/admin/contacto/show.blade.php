@extends('admin.layouts.master')

@section('title', 'Detalles del mensaje')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Detalles del mensaje</h1>
        <a href="{{ route('admin.contactomensaje.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Mensaje de {{ $mensaje->name }}</h6>
            <span class="badge badge-info">{{ $mensaje->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $mensaje->name }}</p>
                    <p><strong>Email:</strong> {{ $mensaje->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $mensaje->phone }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Asunto:</strong> {{ $mensaje->message }}</p>
                    <p><strong>Fecha:</strong> {{ $mensaje->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Mensaje</h6>
                        </div>
                        <div class="card-body">
                            <p>{{ $mensaje->mensaje }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form action="{{ route('admin.contactomensaje.destroy', $mensaje->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este mensaje?')">
                    <i class="fas fa-trash"></i> Eliminar mensaje
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
