@extends('layouts.app')

@section('title', 'Panel Zona Clientes | AIQ')

@section('content')
@php
    $firstName = Str::title(Str::lower(Str::before($cliente->name, ' ')));
    $latestChat = $latestChats->first();
@endphp

<section class="client-zone">
    <div class="client-zone-shell">
        <header class="client-topbar">
            <div class="client-identity">
                <span class="client-avatar">{{ Str::upper(Str::substr($cliente->name, 0, 1)) }}</span>
                <div>
                    <p class="client-welcome">Hola, {{ $firstName }}</p>
                    <div class="client-access">
                        <span class="client-access-dot" aria-hidden="true"></span>
                        Acceso {{ Str::lower($cliente->access_status) }}
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('cliente.logout') }}">
                @csrf
                <button class="client-logout" type="submit">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    Cerrar sesión
                </button>
            </form>
        </header>

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

        <main class="client-dashboard">
            <section class="client-command" aria-labelledby="advisor-title">
                <div class="client-command-copy">
                    <div class="client-ai-mark">
                        <i class="bi bi-stars" aria-hidden="true"></i>
                        <span>Asesor AIQ</span>
                    </div>
                    <h1 id="advisor-title">Tu especialista en productos y procesos, disponible cuando lo necesitás.</h1>
                    <p>Consultá sobre masterbatches, aplicaciones, materiales y procesos. El asesor usa la base técnica de AIQ y conserva el contexto de cada conversación.</p>
                    <div class="client-command-actions">
                        <a href="{{ route('cliente.advisor') }}" class="client-primary-action">
                            <i class="bi bi-chat-square-text" aria-hidden="true"></i>
                            {{ $latestChat ? 'Continuar conversación' : 'Hacer primera consulta' }}
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('cliente.assistant', ['type' => 'tecnico', 'new' => 1]) }}" class="client-secondary-action">
                            <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            Nuevo chat
                        </a>
                    </div>
                </div>

                <div class="client-command-side">
                    <div class="client-orbit" aria-hidden="true">
                        <span class="client-orbit-logo">aiq</span>
                        <span class="client-orbit-dot client-orbit-dot--one"></span>
                        <span class="client-orbit-dot client-orbit-dot--two"></span>
                    </div>
                    <dl class="client-stats">
                        <div>
                            <dt>Consultas realizadas</dt>
                            <dd>{{ number_format($consultasCount, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt>Conversaciones</dt>
                            <dd>{{ number_format($chatsCount, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </section>

            <section class="client-shortcuts" aria-label="Tipos de consulta">
                <a href="{{ route('cliente.assistant', ['type' => 'tecnico', 'new' => 1]) }}">
                    <span class="client-shortcut-icon"><i class="bi bi-box-seam"></i></span>
                    <span><strong>Elegir un producto</strong><small>Encontrá la alternativa adecuada</small></span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('cliente.assistant', ['type' => 'tecnico', 'new' => 1]) }}">
                    <span class="client-shortcut-icon"><i class="bi bi-sliders2"></i></span>
                    <span><strong>Resolver una aplicación</strong><small>Revisá requerimientos y proceso</small></span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('cliente.assistant', ['type' => 'tecnico', 'new' => 1]) }}">
                    <span class="client-shortcut-icon"><i class="bi bi-journal-text"></i></span>
                    <span><strong>Consultar información técnica</strong><small>Accedé al conocimiento de AIQ</small></span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </section>

            <div class="client-content-grid">
                <section class="client-panel client-panel--chats">
                    <div class="client-panel-heading">
                        <div>
                            <h2>Tus conversaciones</h2>
                            <p>Retomá una consulta sin perder el contexto.</p>
                        </div>
                        <a href="{{ route('cliente.advisor') }}">Ver todas <i class="bi bi-arrow-right"></i></a>
                    </div>

                    <div class="client-chat-list">
                        @forelse($latestChats as $chat)
                            <a href="{{ route('cliente.assistant', ['type' => $chat->assistant_type, 'chat' => $chat->id]) }}" class="client-chat-row">
                                <span class="client-chat-icon"><i class="bi bi-chat-left-text"></i></span>
                                <span class="client-chat-copy">
                                    <strong>{{ $chat->title ?: 'Chat sin título' }}</strong>
                                    <small>{{ optional($chat->last_message_at ?: $chat->updated_at)->diffForHumans() }}</small>
                                </span>
                                <span class="client-chat-open">Abrir <i class="bi bi-arrow-up-right"></i></span>
                            </a>
                        @empty
                            <div class="client-empty-state">
                                <span><i class="bi bi-chat-square-text"></i></span>
                                <div>
                                    <strong>Tu historial empieza con una consulta</strong>
                                    <p>Creá un chat y tus conversaciones aparecerán en este espacio.</p>
                                </div>
                                <a href="{{ route('cliente.assistant', ['type' => 'tecnico', 'new' => 1]) }}">Crear chat</a>
                            </div>
                        @endforelse
                    </div>
                </section>

                <aside class="client-panel client-panel--activity">
                    <div class="client-panel-heading">
                        <div>
                            <h2>Actividad reciente</h2>
                            <p>Tus últimas preguntas.</p>
                        </div>
                    </div>

                    <div class="client-timeline">
                        @forelse($ultimasConsultas as $consulta)
                            <div class="client-timeline-item">
                                <span class="client-timeline-dot" aria-hidden="true"></span>
                                <time>{{ $consulta->created_at->diffForHumans() }}</time>
                                <p>{{ Str::limit($consulta->input, 92) }}</p>
                            </div>
                        @empty
                            <div class="client-empty-state client-empty-state--compact">
                                <span><i class="bi bi-lightning-charge"></i></span>
                                <div><strong>Sin actividad todavía</strong><p>Tus consultas recientes aparecerán acá.</p></div>
                            </div>
                        @endforelse
                    </div>
                </aside>
            </div>
        </main>
    </div>
</section>

<style>
    .client-zone {
        --cz-ink: #101828;
        --cz-muted: #586579;
        --cz-blue: #0c58a1;
        --cz-blue-dark: #073c70;
        --cz-red: #c90c18;
        --cz-line: #dfe6ef;
        background:
            radial-gradient(circle at 78% 8%, rgba(12, 88, 161, .08), transparent 30%),
            #f5f7fa;
        color: var(--cz-ink);
        min-height: calc(100vh - 100px);
        padding: 30px 0 64px;
    }
    .client-zone-shell { margin: 0 auto; max-width: 1240px; width: min(1240px, calc(100% - 48px)); }
    .client-topbar { align-items: center; display: flex; justify-content: space-between; margin-bottom: 22px; }
    .client-identity { align-items: center; display: flex; gap: 12px; }
    .client-avatar {
        align-items: center; background: #fff; border: 1px solid var(--cz-line); border-radius: 14px;
        box-shadow: 0 5px 18px rgba(16, 24, 40, .06); color: var(--cz-blue); display: flex;
        font-size: 15px; font-weight: 800; height: 46px; justify-content: center; width: 46px;
    }
    .client-welcome { font-size: 17px; font-weight: 800; line-height: 1.2; margin: 0 0 4px; }
    .client-access { align-items: center; color: var(--cz-muted); display: flex; font-size: 12px; font-weight: 600; gap: 6px; }
    .client-access-dot { background: #16a36a; border-radius: 50%; box-shadow: 0 0 0 3px rgba(22, 163, 106, .12); height: 7px; width: 7px; }
    .client-logout {
        align-items: center; background: transparent; border: 0; border-radius: 10px; color: #4b5565;
        display: inline-flex; font-size: 13px; font-weight: 700; gap: 8px; padding: 10px 12px;
    }
    .client-logout:hover, .client-logout:focus-visible { background: #fff; color: var(--cz-ink); outline: 2px solid rgba(12, 88, 161, .2); }
    .client-dashboard { display: grid; gap: 18px; }
    .client-command {
        background: #0d3157; border: 1px solid rgba(255, 255, 255, .08); border-radius: 24px;
        box-shadow: 0 24px 60px rgba(7, 39, 72, .2); color: #fff; display: grid; gap: 40px;
        grid-template-columns: minmax(0, 1.5fr) minmax(310px, .7fr); overflow: hidden; padding: 46px 48px; position: relative;
    }
    .client-command::after {
        border: 1px solid rgba(255, 255, 255, .08); border-radius: 50%; content: ""; height: 420px;
        position: absolute; right: -180px; top: -220px; width: 420px;
    }
    .client-command-copy { position: relative; z-index: 1; }
    .client-ai-mark { align-items: center; color: #c8ddf2; display: flex; font-size: 12px; font-weight: 800; gap: 9px; margin-bottom: 21px; text-transform: uppercase; }
    .client-ai-mark i { align-items: center; background: #fff; border-radius: 10px; color: var(--cz-blue); display: inline-flex; font-size: 17px; height: 34px; justify-content: center; width: 34px; }
    .client-command h1 { color: #fff; font-size: 36px; font-weight: 800; letter-spacing: -.025em; line-height: 1.12; margin: 0 0 16px; max-width: 780px; text-wrap: balance; }
    .client-command-copy > p { color: #c8d5e3; font-size: 15px; line-height: 1.7; margin: 0; max-width: 720px; text-wrap: pretty; }
    .client-command-actions { display: flex; flex-wrap: wrap; gap: 11px; margin-top: 28px; }
    .client-command-actions a { align-items: center; border-radius: 12px; display: inline-flex; font-size: 14px; font-weight: 750; gap: 10px; min-height: 48px; padding: 0 18px; text-decoration: none; transition: transform 180ms ease, background 180ms ease; }
    .client-primary-action { background: var(--cz-red); color: #fff !important; box-shadow: 0 10px 24px rgba(201, 12, 24, .25); }
    .client-primary-action .bi-arrow-right { margin-left: 5px; }
    .client-primary-action:hover { background: #ad0813; transform: translateY(-1px); }
    .client-secondary-action { border: 1px solid rgba(255, 255, 255, .3); color: #fff !important; }
    .client-secondary-action:hover { background: rgba(255, 255, 255, .1); }
    .client-command-side { align-items: center; align-self: stretch; display: grid; gap: 24px; grid-template-columns: 1fr; position: relative; z-index: 1; }
    .client-orbit {
        align-items: center; border: 1px solid rgba(255, 255, 255, .16); border-radius: 50%; display: flex;
        height: 130px; justify-content: center; justify-self: center; position: relative; width: 130px;
    }
    .client-orbit::before { border: 1px solid rgba(255, 255, 255, .09); border-radius: 50%; content: ""; height: 92px; position: absolute; width: 92px; }
    .client-orbit-logo { align-items: center; background: #fff; border-radius: 50%; color: var(--cz-blue); display: flex; font-size: 24px; font-style: italic; font-weight: 800; height: 62px; justify-content: center; width: 62px; }
    .client-orbit-dot { background: #f2cf23; border: 3px solid #0d3157; border-radius: 50%; height: 13px; position: absolute; width: 13px; }
    .client-orbit-dot--one { right: 9px; top: 22px; } .client-orbit-dot--two { background: #e22b31; bottom: 5px; left: 29px; }
    .client-stats { display: grid; grid-template-columns: repeat(2, 1fr); margin: 0; }
    .client-stats div { padding: 2px 16px; }
    .client-stats div + div { border-left: 1px solid rgba(255, 255, 255, .16); }
    .client-stats dt { color: #aebed0; font-size: 11px; font-weight: 600; }
    .client-stats dd { color: #fff; font-size: 28px; font-weight: 800; line-height: 1; margin: 8px 0 0; }
    .client-shortcuts { display: grid; gap: 12px; grid-template-columns: repeat(3, 1fr); }
    .client-shortcuts > a {
        align-items: center; background: #fff; border: 1px solid var(--cz-line); border-radius: 16px; color: var(--cz-ink);
        display: grid; gap: 12px; grid-template-columns: 42px minmax(0, 1fr) auto; padding: 16px; text-decoration: none;
        transition: border-color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
    }
    .client-shortcuts > a:hover { border-color: #b8cbe0; box-shadow: 0 12px 30px rgba(16, 24, 40, .07); color: var(--cz-ink); transform: translateY(-2px); }
    .client-shortcut-icon { align-items: center; background: #edf4fb; border-radius: 12px; color: var(--cz-blue); display: flex; font-size: 17px; height: 42px; justify-content: center; width: 42px; }
    .client-shortcuts strong, .client-shortcuts small { display: block; }
    .client-shortcuts strong { font-size: 13px; font-weight: 800; }
    .client-shortcuts small { color: var(--cz-muted); font-size: 11px; margin-top: 3px; }
    .client-shortcuts > a > .bi-chevron-right { color: #9ba7b6; font-size: 12px; }
    .client-content-grid { display: grid; gap: 18px; grid-template-columns: minmax(0, 1.65fr) minmax(300px, .75fr); }
    .client-panel { background: #fff; border: 1px solid var(--cz-line); border-radius: 20px; box-shadow: 0 12px 35px rgba(16, 24, 40, .05); padding: 24px; }
    .client-panel-heading { align-items: flex-start; display: flex; justify-content: space-between; margin-bottom: 18px; }
    .client-panel-heading h2 { font-size: 20px; font-weight: 800; margin: 0; }
    .client-panel-heading p { color: var(--cz-muted); font-size: 12px; margin: 5px 0 0; }
    .client-panel-heading > a { color: var(--cz-blue); font-size: 12px; font-weight: 800; text-decoration: none; }
    .client-chat-list { display: grid; gap: 8px; }
    .client-chat-row {
        align-items: center; border: 1px solid transparent; border-radius: 14px; color: var(--cz-ink); display: grid;
        gap: 13px; grid-template-columns: 44px minmax(0, 1fr) auto; padding: 12px; text-decoration: none;
    }
    .client-chat-row:hover { background: #f6f9fc; border-color: #e3ebf3; color: var(--cz-ink); }
    .client-chat-icon { align-items: center; background: #edf4fb; border-radius: 12px; color: var(--cz-blue); display: flex; height: 44px; justify-content: center; width: 44px; }
    .client-chat-copy { min-width: 0; }
    .client-chat-copy strong { display: block; font-size: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .client-chat-copy small { color: var(--cz-muted); display: block; font-size: 11px; margin-top: 4px; }
    .client-chat-open { color: var(--cz-blue); font-size: 12px; font-weight: 750; }
    .client-timeline { padding-left: 3px; }
    .client-timeline-item { border-left: 1px solid #dfe6ef; margin-left: 5px; padding: 0 0 18px 20px; position: relative; }
    .client-timeline-item:last-child { border-color: transparent; padding-bottom: 0; }
    .client-timeline-dot { background: var(--cz-blue); border: 3px solid #e7f0f9; border-radius: 50%; height: 11px; left: -6px; position: absolute; top: 2px; width: 11px; }
    .client-timeline-item time { color: #778396; display: block; font-size: 10px; font-weight: 700; }
    .client-timeline-item p { color: #263244; font-size: 12px; line-height: 1.45; margin: 5px 0 0; }
    .client-empty-state { align-items: center; background: #f7f9fc; border-radius: 14px; display: flex; gap: 14px; padding: 18px; }
    .client-empty-state > span { align-items: center; background: #e8f1fa; border-radius: 11px; color: var(--cz-blue); display: flex; flex: 0 0 auto; height: 42px; justify-content: center; width: 42px; }
    .client-empty-state strong { display: block; font-size: 13px; }
    .client-empty-state p { color: var(--cz-muted); font-size: 11px; margin: 4px 0 0; }
    .client-empty-state > a { color: var(--cz-blue); font-size: 12px; font-weight: 800; margin-left: auto; }
    .client-empty-state--compact { align-items: flex-start; padding: 14px; }
    @media (max-width: 1024px) {
        .client-command { grid-template-columns: 1fr; }
        .client-command-side { grid-template-columns: 160px minmax(260px, 1fr); justify-content: start; }
        .client-shortcuts { grid-template-columns: 1fr; }
        .client-content-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .client-zone { padding: 20px 0 44px; }
        .client-zone-shell { width: min(100% - 28px, 1240px); }
        .client-command { border-radius: 20px; gap: 30px; padding: 30px 24px; }
        .client-command h1 { font-size: 28px; }
        .client-command-side { grid-template-columns: 1fr; }
        .client-orbit { display: none; }
        .client-stats div:first-child { padding-left: 0; }
        .client-command-actions a { justify-content: center; width: 100%; }
        .client-panel { padding: 19px; }
        .client-chat-row { grid-template-columns: 42px minmax(0, 1fr); }
        .client-chat-open { display: none; }
        .client-empty-state { align-items: flex-start; flex-wrap: wrap; }
        .client-empty-state > a { margin-left: 56px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .client-command-actions a, .client-shortcuts > a { transition: none; }
    }
</style>
@endsection
