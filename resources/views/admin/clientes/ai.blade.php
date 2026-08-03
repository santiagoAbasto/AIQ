@extends('admin.layouts.master')

@section('title', 'Uso de IA')

@push('styles')
<style>
    .ai-analytics { --ai-green: #16845b; --ai-amber: #b86d09; }
    .ai-analytics__hero { align-items: center; }
    .ai-analytics__actions { display: flex; flex-wrap: wrap; gap: 10px; }
    .ai-metrics {
        display: grid;
        grid-template-columns: 1.35fr repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }
    .ai-metric {
        min-height: 142px;
        padding: 20px;
        border: 1px solid var(--admin-border);
        border-radius: 18px;
        background: var(--admin-surface);
        box-shadow: 0 10px 28px rgba(15, 29, 51, .06);
    }
    .ai-metric--primary { background: var(--admin-blue); border-color: var(--admin-blue); color: #fff; }
    .ai-metric__top { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .ai-metric__label { margin: 0; color: var(--admin-muted); font-size: 13px; font-weight: 700; }
    .ai-metric--primary .ai-metric__label { color: rgba(255, 255, 255, .78); }
    .ai-metric__icon {
        width: 36px; height: 36px; display: grid; place-items: center;
        border-radius: 11px; background: rgba(12, 88, 161, .1); color: var(--admin-blue);
    }
    .ai-metric--primary .ai-metric__icon { background: rgba(255, 255, 255, .15); color: #fff; }
    .ai-metric__value { margin: 18px 0 2px; font-size: 31px; font-weight: 800; line-height: 1; }
    .ai-metric__note { margin: 8px 0 0; color: var(--admin-muted); font-size: 12px; font-weight: 600; }
    .ai-metric--primary .ai-metric__note { color: rgba(255, 255, 255, .78); }
    .ai-trend { color: #c9f6e4; font-weight: 800; }
    .ai-trend--down { color: #ffd9d9; }
    .ai-overview { display: grid; grid-template-columns: minmax(0, 1.8fr) minmax(260px, .8fr); gap: 18px; margin-bottom: 28px; }
    .ai-panel { border: 1px solid var(--admin-border); border-radius: 18px; background: #fff; box-shadow: var(--admin-shadow); }
    .ai-panel__header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; padding: 20px 22px 0; }
    .ai-panel__title { margin: 0; color: var(--admin-text); font-size: 17px; font-weight: 800; }
    .ai-panel__subtitle { margin: 5px 0 0; color: var(--admin-muted); font-size: 12px; }
    .ai-chart { height: 250px; padding: 28px 22px 20px; display: flex; align-items: flex-end; gap: clamp(5px, 1vw, 13px); }
    .ai-chart__day { flex: 1; min-width: 0; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 8px; }
    .ai-chart__value { color: var(--admin-text); font-size: 11px; font-weight: 800; }
    .ai-chart__track { width: 100%; max-width: 30px; height: 170px; display: flex; align-items: flex-end; border-radius: 8px; background: #eef3f8; overflow: hidden; }
    .ai-chart__bar { width: 100%; min-height: 3px; border-radius: 8px; background: var(--admin-blue); transition: opacity 180ms ease; }
    .ai-chart__day:hover .ai-chart__bar { opacity: .72; }
    .ai-chart__label { color: var(--admin-muted); font-size: 10px; white-space: nowrap; }
    .ai-ranking { padding: 16px 22px 20px; }
    .ai-ranking__row { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; padding: 13px 0; border-bottom: 1px solid var(--admin-border); }
    .ai-ranking__row:last-child { border-bottom: 0; }
    .ai-ranking__name { margin: 0; color: var(--admin-text); font-size: 13px; font-weight: 800; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ai-ranking__meta { margin: 3px 0 0; color: var(--admin-muted); font-size: 11px; }
    .ai-ranking__count { color: var(--admin-blue); font-size: 16px; font-weight: 800; }
    .ai-threads__header { display: flex; align-items: end; justify-content: space-between; gap: 18px; margin-bottom: 14px; }
    .ai-filter { display: flex; align-items: end; gap: 8px; }
    .ai-filter .form-select { min-width: 250px; }
    .ai-thread { margin-bottom: 10px; border: 1px solid var(--admin-border); border-radius: 16px; background: #fff; overflow: hidden; }
    .ai-thread[open] { border-color: var(--admin-border-strong); box-shadow: 0 12px 30px rgba(15, 29, 51, .07); }
    .ai-thread__summary {
        display: grid; grid-template-columns: minmax(230px, 1.2fr) minmax(150px, .7fr) auto auto;
        align-items: center; gap: 18px; padding: 17px 20px; cursor: pointer; list-style: none;
    }
    .ai-thread__summary::-webkit-details-marker { display: none; }
    .ai-thread__client { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .ai-thread__avatar { width: 38px; height: 38px; flex: 0 0 auto; display: grid; place-items: center; border-radius: 12px; background: #eaf2fa; color: var(--admin-blue); font-size: 12px; font-weight: 800; }
    .ai-thread__title { margin: 0; overflow: hidden; color: var(--admin-text); font-size: 14px; font-weight: 800; text-overflow: ellipsis; white-space: nowrap; }
    .ai-thread__email, .ai-thread__date { margin: 3px 0 0; color: var(--admin-muted); font-size: 11px; }
    .ai-thread__count { color: var(--admin-text); font-size: 12px; font-weight: 750; white-space: nowrap; }
    .ai-badge { display: inline-flex; align-items: center; gap: 5px; padding: 6px 9px; border-radius: 999px; background: #e9f7f1; color: var(--ai-green); font-size: 11px; font-weight: 800; white-space: nowrap; }
    .ai-badge--hidden { background: #fff4e3; color: var(--ai-amber); }
    .ai-thread__chevron { color: var(--admin-muted); transition: transform 180ms ease; }
    .ai-thread[open] .ai-thread__chevron { transform: rotate(180deg); }
    .ai-thread__body { padding: 4px 20px 20px; border-top: 1px solid var(--admin-border); background: var(--admin-surface-soft); }
    .ai-message { max-width: 82%; margin-top: 14px; padding: 12px 14px; border: 1px solid var(--admin-border); border-radius: 14px; background: #fff; color: var(--admin-text); font-size: 13px; line-height: 1.55; white-space: pre-wrap; }
    .ai-message--assistant { margin-left: auto; border-color: #cfe0f1; background: #eaf2fa; }
    .ai-message__role { display: block; margin-bottom: 5px; color: var(--admin-muted); font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .ai-empty { padding: 42px 20px; text-align: center; color: var(--admin-muted); }
    @media (max-width: 1199.98px) { .ai-metrics { grid-template-columns: repeat(2, 1fr); } .ai-overview { grid-template-columns: 1fr; } }
    @media (max-width: 767.98px) {
        .ai-metrics { grid-template-columns: 1fr; }
        .ai-threads__header, .ai-filter { align-items: stretch; flex-direction: column; }
        .ai-filter .form-select { min-width: 0; }
        .ai-thread__summary { grid-template-columns: 1fr auto; gap: 12px; }
        .ai-thread__summary > :nth-child(2) { display: none; }
        .ai-thread__count { grid-column: 1; padding-left: 50px; }
        .ai-thread__chevron { grid-column: 2; grid-row: 1 / span 2; }
        .ai-message { max-width: 96%; }
        .ai-chart { gap: 4px; padding-left: 12px; padding-right: 12px; }
        .ai-chart__label { transform: rotate(-45deg); transform-origin: center; }
    }
    @media (prefers-reduced-motion: reduce) { .ai-chart__bar, .ai-thread__chevron { transition: none; } }
</style>
@endpush

@section('content')
<div class="ai-analytics">
    <header class="admin-page-hero ai-analytics__hero">
        <div>
            <p class="admin-page-kicker">Zona Clientes</p>
            <h1 class="admin-page-title">Uso de IA</h1>
            <p class="admin-page-description">Actividad, adopción e historial completo de conversaciones. Los hilos ocultos por clientes permanecen disponibles aquí.</p>
        </div>
        <div class="ai-analytics__actions">
            <a href="{{ route('admin.clientes.knowledge') }}" class="btn btn-outline-primary"><i class="bi bi-database me-2"></i>Base de conocimiento</a>
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Volver a clientes</a>
        </div>
    </header>

    <section class="ai-metrics" aria-label="Resumen de uso">
        <article class="ai-metric ai-metric--primary">
            <div class="ai-metric__top">
                <p class="ai-metric__label">Consultas en 14 días</p>
                <span class="ai-metric__icon"><i class="bi bi-stars"></i></span>
            </div>
            <p class="ai-metric__value">{{ number_format($periodRequests, 0, ',', '.') }}</p>
            <p class="ai-metric__note"><span class="ai-trend {{ $periodChange < 0 ? 'ai-trend--down' : '' }}">{{ $periodChange >= 0 ? '+' : '' }}{{ $periodChange }}%</span> frente a los 14 días anteriores</p>
        </article>
        <article class="ai-metric">
            <div class="ai-metric__top"><p class="ai-metric__label">Clientes activos</p><span class="ai-metric__icon"><i class="bi bi-people"></i></span></div>
            <p class="ai-metric__value">{{ number_format($activeClients, 0, ',', '.') }}</p>
            <p class="ai-metric__note">Con al menos una consulta reciente</p>
        </article>
        <article class="ai-metric">
            <div class="ai-metric__top"><p class="ai-metric__label">Hilos conservados</p><span class="ai-metric__icon"><i class="bi bi-chat-square-text"></i></span></div>
            <p class="ai-metric__value">{{ number_format($totalChats, 0, ',', '.') }}</p>
            <p class="ai-metric__note">{{ number_format($hiddenChats, 0, ',', '.') }} ocultos por clientes</p>
        </article>
        <article class="ai-metric">
            <div class="ai-metric__top"><p class="ai-metric__label">Respuestas completadas</p><span class="ai-metric__icon"><i class="bi bi-check2-circle"></i></span></div>
            <p class="ai-metric__value">{{ number_format($successRate, 1, ',', '.') }}%</p>
            <p class="ai-metric__note">{{ number_format($totalRequests, 0, ',', '.') }} consultas históricas</p>
        </article>
    </section>

    <section class="ai-overview">
        <article class="ai-panel">
            <div class="ai-panel__header">
                <div><h2 class="ai-panel__title">Consultas por día</h2><p class="ai-panel__subtitle">Últimos 14 días</p></div>
            </div>
            <div class="ai-chart" role="img" aria-label="Gráfico de consultas diarias de los últimos 14 días">
                @foreach($usageSeries as $point)
                    <div class="ai-chart__day" title="{{ $point['label'] }}: {{ $point['total'] }} consultas">
                        <span class="ai-chart__value">{{ $point['total'] }}</span>
                        <span class="ai-chart__track"><span class="ai-chart__bar" style="height: {{ max(2, round(($point['total'] / $maxDailyUsage) * 100)) }}%"></span></span>
                        <span class="ai-chart__label">{{ $point['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </article>
        <article class="ai-panel">
            <div class="ai-panel__header">
                <div><h2 class="ai-panel__title">Clientes con más uso</h2><p class="ai-panel__subtitle">Consultas en los últimos 14 días</p></div>
            </div>
            <div class="ai-ranking">
                @forelse($topClients as $client)
                    <div class="ai-ranking__row">
                        <div>
                            <p class="ai-ranking__name">{{ $client->name }}</p>
                            <p class="ai-ranking__meta">{{ $client->ai_chats_count }} {{ Str::plural('hilo', $client->ai_chats_count) }}</p>
                        </div>
                        <span class="ai-ranking__count">{{ $client->period_requests_count }}</span>
                    </div>
                @empty
                    <div class="ai-empty">Todavía no hay actividad en este período.</div>
                @endforelse
            </div>
        </article>
    </section>

    <section aria-labelledby="threads-title">
        <div class="ai-threads__header">
            <div>
                <h2 id="threads-title" class="ai-panel__title">Hilos por cliente</h2>
                <p class="ai-panel__subtitle">Abrí un hilo para revisar la conversación completa.</p>
            </div>
            <form method="GET" action="{{ route('admin.clientes.ai') }}" class="ai-filter">
                <div>
                    <label for="cliente">Filtrar por cliente</label>
                    <select id="cliente" name="cliente" class="form-select">
                        <option value="">Todos los clientes</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected($clientId === $client->id)>{{ $client->name }} · {{ $client->email }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Aplicar filtro</button>
                @if($clientId)
                    <a class="btn btn-outline-secondary" href="{{ route('admin.clientes.ai') }}">Limpiar</a>
                @endif
            </form>
        </div>

        @forelse($threads as $thread)
            <details class="ai-thread">
                <summary class="ai-thread__summary">
                    <div class="ai-thread__client">
                        <span class="ai-thread__avatar">{{ Str::upper(Str::substr($thread->logincliente?->name ?: 'C', 0, 2)) }}</span>
                        <div class="min-w-0">
                            <p class="ai-thread__title">{{ $thread->title ?: 'Conversación sin título' }}</p>
                            <p class="ai-thread__email">{{ $thread->logincliente?->name }} · {{ $thread->logincliente?->email }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="ai-badge {{ $thread->hidden_from_client_at ? 'ai-badge--hidden' : '' }}">
                            <i class="bi {{ $thread->hidden_from_client_at ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                            {{ $thread->hidden_from_client_at ? 'Oculto por cliente' : 'Visible al cliente' }}
                        </span>
                    </div>
                    <div class="ai-thread__count">
                        {{ $thread->messages_count }} {{ Str::plural('mensaje', $thread->messages_count) }}
                        <p class="ai-thread__date">{{ optional($thread->last_message_at ?: $thread->created_at)->format('d/m/Y H:i') }}</p>
                    </div>
                    <i class="bi bi-chevron-down ai-thread__chevron" aria-hidden="true"></i>
                </summary>
                <div class="ai-thread__body">
                    @forelse($thread->messages as $message)
                        <div class="ai-message {{ $message->role === 'assistant' ? 'ai-message--assistant' : '' }}">
                            <span class="ai-message__role">{{ $message->role === 'assistant' ? 'Asesor AIQ' : 'Cliente' }} · {{ $message->created_at->format('d/m/Y H:i') }}</span>
                            @if($message->attachment_path)
                                <a href="{{ Storage::disk('public')->url($message->attachment_path) }}" target="_blank" rel="noopener" class="d-block mb-2">
                                    <img src="{{ Storage::disk('public')->url($message->attachment_path) }}" alt="Imagen adjunta" style="display:block;max-width:320px;max-height:240px;object-fit:cover;border-radius:10px;">
                                </a>
                            @endif
                            {{ $message->content }}
                        </div>
                    @empty
                        <div class="ai-empty">Este hilo no tiene mensajes guardados.</div>
                    @endforelse
                </div>
            </details>
        @empty
            <div class="ai-panel ai-empty">No hay hilos para el filtro seleccionado.</div>
        @endforelse

        {{ $threads->onEachSide(1)->links('admin.partials.pagination') }}
    </section>
</div>
@endsection
