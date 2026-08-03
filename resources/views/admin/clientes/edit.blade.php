@extends('admin.layouts.master')

@section('content')
@php
    $currentStatus = old('access_status', ! $cliente->is_enabled ? 'pending' : ($cliente->access_unlimited ? 'active_unlimited' : 'active_limited'));
@endphp

<h3>Editar Cliente</h3>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.clientes.update', $cliente) }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $cliente->name) }}" required>
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $cliente->email) }}" required>
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="company">Empresa</label>
            <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $cliente->company) }}">
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="phone">Teléfono</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $cliente->phone) }}">
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
    </div>

    <hr>
    <h5>Permisos de acceso</h5>
    <div class="row">
        <div class="col-12 col-md-4 form-group mb-3">
            <label for="access_status">Estado</label>
            <select class="form-control" id="access_status" name="access_status" required>
                <option value="pending" @selected($currentStatus === 'pending')>Pendiente</option>
                <option value="active_unlimited" @selected($currentStatus === 'active_unlimited')>Activo ilimitado</option>
                <option value="active_limited" @selected($currentStatus === 'active_limited')>Activo con vencimiento</option>
                <option value="disabled" @selected($currentStatus === 'disabled')>Deshabilitado</option>
            </select>
        </div>
        <div class="col-12 col-md-4 form-group mb-3">
            <label for="access_days">Días de permanencia</label>
            <input type="number" class="form-control" id="access_days" name="access_days" min="1" value="{{ old('access_days') }}">
        </div>
        <div class="col-12 col-md-4 form-group mb-3">
            <label for="access_expires_at">Fecha de vencimiento</label>
            <input type="date" class="form-control" id="access_expires_at" name="access_expires_at" value="{{ old('access_expires_at', optional($cliente->access_expires_at)->format('Y-m-d')) }}">
        </div>
    </div>

    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
