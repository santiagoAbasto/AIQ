@php
    $loginLogo = \App\Models\Logo::query()->first();
    $loginLogoPath = $loginLogo?->logo_header ?: $loginLogo?->logo_footer;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=20260706" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20260706" type="image/x-icon">
    <title>Ingresar al administrador</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">

    <style>
        body.admin-login-body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at 12% 18%, rgba(12, 88, 161, 0.18), transparent 32%),
                linear-gradient(135deg, #f7fafc 0%, #eef4fa 46%, #ffffff 100%);
            color: var(--admin-text);
            font-family: "Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .admin-login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(420px, 0.75fr);
        }

        .admin-login-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px;
            background:
                radial-gradient(circle at 18% 18%, rgba(251, 13, 27, 0.26), transparent 32%),
                linear-gradient(145deg, #101b2f 0%, #152640 48%, #0c58a1 100%);
            color: #fff;
        }

        .admin-login-logo {
            width: 92px;
            height: 92px;
            border-radius: 24px;
            object-fit: contain;
            padding: 8px;
            background: #fff;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.22);
        }

        .admin-login-logo-fallback {
            width: 92px;
            height: 92px;
            border-radius: 24px;
            display: grid;
            place-items: center;
            background: #fff;
            color: var(--admin-blue);
            font-size: 28px;
            font-weight: 800;
        }

        .admin-login-panel h1 {
            max-width: 720px;
            margin: 42px 0 18px;
            font-size: clamp(36px, 6vw, 68px);
            font-weight: 800;
            line-height: 0.98;
            letter-spacing: -0.02em;
        }

        .admin-login-panel p {
            max-width: 620px;
            margin: 0;
            color: rgba(255,255,255,0.74);
            font-size: 17px;
            line-height: 1.65;
        }

        .admin-login-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 34px;
        }

        .admin-login-meta span {
            display: inline-flex;
            align-items: center;
            min-height: 36px;
            padding: 0 12px;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 999px;
            color: rgba(255,255,255,0.78);
            font-size: 13px;
            font-weight: 700;
        }

        .admin-login-form-wrap {
            display: grid;
            place-items: center;
            padding: 38px;
        }

        .admin-login-card {
            width: 100%;
            max-width: 460px;
            border: 1px solid var(--admin-border);
            border-radius: 28px;
            background: rgba(255,255,255,0.92);
            box-shadow: 0 24px 70px rgba(15, 29, 51, 0.12);
            padding: 34px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .admin-login-card h2 {
            margin: 0 0 8px;
            color: var(--admin-text);
            font-size: 30px;
            font-weight: 800;
        }

        .admin-login-card .lead {
            margin-bottom: 26px;
            color: var(--admin-muted);
            font-size: 15px;
        }

        .admin-login-card label {
            color: var(--admin-text);
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .admin-login-card .form-control {
            min-height: 52px;
            border: 1px solid var(--admin-border-strong);
            border-radius: 14px;
            color: var(--admin-text);
            font-size: 15px;
            box-shadow: none;
        }

        .admin-login-card .form-control:focus {
            border-color: var(--admin-blue);
            box-shadow: 0 0 0 4px rgba(12, 88, 161, 0.12);
        }

        .admin-login-card .btn {
            min-height: 52px;
            border-radius: 14px;
            background: var(--admin-blue);
            border-color: var(--admin-blue);
            font-weight: 800;
        }

        .admin-login-card .btn:hover {
            background: var(--admin-blue-dark);
            border-color: var(--admin-blue-dark);
        }

        .admin-login-return {
            display: inline-flex;
            align-items: center;
            margin-top: 18px;
            color: var(--admin-blue);
            font-size: 14px;
            font-weight: 800;
        }

        @media (max-width: 991.98px) {
            .admin-login-shell {
                grid-template-columns: 1fr;
            }

            .admin-login-panel {
                padding: 32px;
            }

            .admin-login-panel h1 {
                margin-top: 28px;
            }

            .admin-login-form-wrap {
                padding: 24px;
            }
        }

        @media (max-width: 575.98px) {
            .admin-login-card {
                padding: 24px;
                border-radius: 22px;
            }
        }
    </style>
</head>

<body class="admin-login-body">
    <main class="admin-login-shell">
        <section class="admin-login-panel">
            <div>
                @if($loginLogoPath)
                    <img src="{{ media_url($loginLogoPath) }}" class="admin-login-logo" alt="AIQ">
                @else
                    <div class="admin-login-logo-fallback">AIQ</div>
                @endif

                <h1>Administrá el contenido público de AIQ.</h1>
                <p>Cambiá textos, imágenes, productos, novedades, datos de contacto y accesos de clientes desde un panel ordenado para publicar cambios sin tocar código.</p>
                <div class="admin-login-meta">
                    <span><i class="fa-solid fa-pen-to-square me-2"></i> Edición de contenido</span>
                    <span><i class="fa-solid fa-users-gear me-2"></i> Zona Clientes</span>
                    <span><i class="fa-solid fa-globe me-2"></i> Vista pública</span>
                </div>
            </div>
        </section>

        <section class="admin-login-form-wrap">
            <div class="admin-login-card">
                <h2>Ingresar</h2>
                <p class="lead">Usá tu usuario administrador para entrar al panel.</p>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="admin" value="admin">

                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input id="username"
                               class="form-control"
                               type="text"
                               name="username"
                               placeholder="Ingresá tu usuario"
                               required
                               autofocus
                               autocomplete="username"
                               value="{{ old('username') }}">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password"
                               class="form-control"
                               type="password"
                               name="password"
                               placeholder="Ingresá tu contraseña"
                               required
                               autocomplete="current-password">
                    </div>

                    <button class="btn btn-primary w-100" type="submit">
                        Entrar al panel
                    </button>
                </form>

                <a class="admin-login-return" href="{{ route('index') }}">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Volver al sitio público
                </a>
            </div>
        </section>
    </main>
</body>
</html>
