@extends('layouts.app')

@section('title', 'Solicitar acceso | AIQ')

@section('content')
<section class="client-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="client-panel">
                    <p class="client-kicker">Zona Clientes</p>
                    <h1>Solicitar acceso</h1>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cliente.register.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="name">Nombre</label>
                                <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="company">Empresa</label>
                                <input class="form-control" id="company" name="company" type="text" value="{{ old('company') }}">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="phone">Teléfono</label>
                                <input class="form-control" id="phone" name="phone" type="text" value="{{ old('phone') }}">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="password">Contraseña</label>
                                <input class="form-control" id="password" name="password" type="password" required>
                            </div>
                            <div class="col-12 col-md-6 mb-4">
                                <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                                <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Enviar solicitud</button>
                    </form>

                    <div class="client-auth-footer">
                        <span>¿Ya tenés cuenta?</span>
                        <a href="{{ route('cliente.login') }}">Ingresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .client-auth {
        background: #F7F7F8;
        padding: 72px 0;
    }

    .client-panel {
        background: #fff;
        border: 1px solid #D8DDE6;
        border-radius: 8px;
        padding: 32px;
    }

    .client-kicker {
        color: #0C58A1;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .client-panel h1 {
        color: #151414;
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 24px;
    }

    .client-auth-footer {
        border-top: 1px solid #E6E8EC;
        display: flex;
        justify-content: space-between;
        margin-top: 24px;
        padding-top: 16px;
    }
</style>
@endsection
