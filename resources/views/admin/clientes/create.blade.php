@extends('admin.layouts.master')

@section('content')
<h3>Nuevo Cliente</h3>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.clientes.store') }}">
    @csrf
    <div class="row">
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="company">Empresa</label>
            <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="phone">Teléfono</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="col-12 col-md-6 form-group mb-3">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
    </div>

    <hr>
    <h5>Permisos de acceso</h5>
    <div class="row">
        <div class="col-12 col-md-4 form-group mb-3">
            <label for="access_status">Estado</label>
            <select class="form-control" id="access_status" name="access_status" required>
                <option value="pending" @selected(old('access_status', 'pending') === 'pending')>Pendiente</option>
                <option value="active_unlimited" @selected(old('access_status') === 'active_unlimited')>Activo ilimitado</option>
                <option value="active_limited" @selected(old('access_status') === 'active_limited')>Activo con vencimiento</option>
                <option value="disabled" @selected(old('access_status') === 'disabled')>Deshabilitado</option>
            </select>
        </div>
        <div class="col-12 col-md-4 form-group mb-3">
            <label for="access_days">Días de permanencia</label>
            <input type="number" class="form-control" id="access_days" name="access_days" min="1" value="{{ old('access_days') }}">
        </div>
        <div class="col-12 col-md-4 form-group mb-3">
            <label for="access_expires_at">Fecha de vencimiento</label>
            <input type="date" class="form-control" id="access_expires_at" name="access_expires_at" value="{{ old('access_expires_at') }}">
        </div>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
