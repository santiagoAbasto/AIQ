@extends('layouts.app')

@section('title', 'Zona Clientes | AIQ')

@section('content')
<section class="client-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-5">
                <div class="client-panel">
                    <p class="client-kicker">Zona Clientes</p>
                    <h1>Acceso privado</h1>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cliente.login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Contraseña</label>
                            <input class="form-control" id="password" name="password" type="password" required>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Ingresar</button>
                    </form>

                    <div class="client-auth-footer">
                        <span>¿Necesitás acceso?</span>
                        <a href="{{ route('cliente.register') }}">Solicitar cuenta</a>
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
