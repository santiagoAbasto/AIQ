@extends('layouts.app')

@section('title', $meta['title'].' | AIQ')

@section('content')
<section class="ai-chat-page">
    <div class="ai-chat-shell">
        <div class="ai-chat-topbar">
            <div class="ai-chat-identity">
                <span class="ai-chat-identity-mark" aria-hidden="true"><i class="bi bi-stars"></i></span>
                <div>
                    <h1>{{ $meta['title'] }}</h1>
                    <p>{{ $cliente->name }} · {{ $cliente->access_status }}</p>
                </div>
            </div>
            <div class="ai-chat-actions">
                <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline-dark">
                    <i class="bi bi-grid"></i>
                    Panel
                </a>
                <a href="{{ route('cliente.assistant', ['type' => 'tecnico', 'new' => 1]) }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Nuevo chat
                </a>
            </div>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger mb-4">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <div class="ai-chat-layout">
            <aside class="ai-chat-history" aria-label="Historial de chats">
                <div class="ai-pane-title">
                    <span>Chats</span>
                    <strong>{{ $chats->count() }}</strong>
                </div>

                <a class="ai-new-chat {{ $activeChat ? '' : 'is-active' }}" href="{{ route('cliente.assistant', ['type' => 'tecnico', 'new' => 1]) }}">
                    <i class="bi bi-chat-square-text"></i>
                    Nueva conversación
                </a>

                <div class="ai-history-list">
                    @forelse($chats as $chat)
                        <div class="ai-history-row {{ $activeChat?->id === $chat->id ? 'is-active' : '' }}">
                            <a
                                href="{{ route('cliente.assistant', ['type' => $chat->assistant_type, 'chat' => $chat->id]) }}"
                                class="ai-history-item">
                                <span>{{ $chat->title ?: 'Chat sin título' }}</span>
                                <time>{{ optional($chat->last_message_at ?: $chat->updated_at)->format('d/m H:i') }}</time>
                            </a>
                            <form
                                method="POST"
                                action="{{ route('cliente.assistant.chat.destroy', ['type' => $chat->assistant_type, 'chat' => $chat->id]) }}"
                                class="ai-history-delete"
                                onsubmit="return confirm('¿Eliminar este chat? Esta acción no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                @if($activeChat)
                                    <input type="hidden" name="current_chat_id" value="{{ $activeChat->id }}">
                                @endif
                                <button type="submit" aria-label="Eliminar chat {{ $chat->title ?: 'sin título' }}" title="Eliminar chat">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="ai-empty-small">Todavía no hay conversaciones.</p>
                    @endforelse
                </div>
            </aside>

            <main class="ai-chat-main" aria-label="Conversación">
                <div class="ai-chat-header">
                    <div>
                        <h2>{{ $activeChat?->title ?: 'Nueva conversación' }}</h2>
                        <p><span class="ai-online-dot" aria-hidden="true"></span> {{ $meta['description'] }}</p>
                    </div>
                </div>

                <div class="ai-messages" id="aiMessages">
                    @if($messages->isEmpty())
                        <div class="ai-welcome">
                            <div class="ai-welcome-mark">
                                <i class="bi bi-stars"></i>
                            </div>
                            <h2>¿Qué necesitás resolver?</h2>
                            <p>Contame tu consulta y el asesor te responde en esta conversación.</p>
                        </div>
                    @else
                        @foreach($messages as $message)
                            <article class="ai-message ai-message--{{ $message->role }}">
                                <div class="ai-message-avatar">
                                    @if($message->role === 'user')
                                        {{ Str::upper(Str::substr($cliente->name, 0, 1)) }}
                                    @else
                                        <i class="bi bi-stars"></i>
                                    @endif
                                </div>
                                <div class="ai-message-body">
                                    <div class="ai-message-meta">
                                        <strong>{{ $message->role === 'user' ? 'Vos' : 'AIQ' }}</strong>
                                        <time>{{ $message->created_at->format('d/m/Y H:i') }}</time>
                                    </div>
                                    @if($message->attachment_path)
                                        <a
                                            class="ai-message-image"
                                            href="{{ Storage::disk('public')->url($message->attachment_path) }}"
                                            target="_blank"
                                            rel="noopener"
                                            aria-label="Abrir imagen adjunta">
                                            <img src="{{ Storage::disk('public')->url($message->attachment_path) }}" alt="Imagen adjunta por el cliente">
                                            <span><i class="bi bi-arrows-fullscreen"></i> Ver imagen completa</span>
                                        </a>
                                    @endif
                                    @if(filled($message->content))
                                        <div class="ai-message-bubble {{ $message->role === 'assistant' ? 'ai-message-markdown' : '' }}">
                                            @if($message->role === 'assistant')
                                                {!! Str::markdown($message->content, [
                                                    'html_input' => 'strip',
                                                    'allow_unsafe_links' => false,
                                                ]) !!}
                                            @else
                                                {!! nl2br(e($message->content)) !!}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    @endif
                </div>

                <form method="POST" action="{{ route('cliente.assistant.ask', $type) }}" class="ai-composer" id="aiComposer" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    @if($activeChat)
                        <input type="hidden" name="chat_id" value="{{ $activeChat->id }}">
                    @endif
                    <div class="ai-image-preview" id="aiImagePreview" hidden>
                        <img id="aiImagePreviewImg" alt="Vista previa de la imagen seleccionada">
                        <div>
                            <strong id="aiImagePreviewName">Imagen seleccionada</strong>
                            <span>AIQ analizará la pieza, el color, la textura y posibles defectos visibles.</span>
                        </div>
                        <button type="button" id="aiImageRemove" aria-label="Quitar imagen"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <label class="ai-attach-button" for="image" aria-label="Adjuntar una imagen" title="Adjuntar imagen">
                        <i class="bi bi-image"></i>
                    </label>
                    <input class="visually-hidden" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                    <label class="visually-hidden" for="input">{{ $meta['label'] }}</label>
                    <textarea
                        id="input"
                        name="input"
                        rows="1"
                        autocomplete="off"
                        placeholder="Describí la pieza, el material o el problema. También podés adjuntar una foto.">{{ old('input') }}</textarea>
                    <button type="submit" aria-label="Enviar consulta">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </main>
        </div>
    </div>
</section>

<style>
    .ai-chat-page {
        background:
            radial-gradient(circle at 75% 0, rgba(12, 88, 161, .07), transparent 30%),
            #F4F7FA;
        color: #101828;
        display: flex;
        min-height: 0;
        overflow: visible;
        padding: 10px 0 18px;
    }

    .ai-chat-shell {
        display: flex;
        flex-direction: column;
        gap: 9px;
        margin: 0 auto;
        max-width: 1280px;
        width: min(1280px, calc(100% - 48px));
    }

    .ai-chat-topbar {
        align-items: center;
        display: flex;
        flex: 0 0 auto;
        justify-content: space-between;
        gap: 14px;
    }

    .ai-chat-identity {
        align-items: center;
        display: flex;
        gap: 11px;
        min-width: 0;
    }

    .ai-chat-identity-mark {
        align-items: center;
        background: #EAF3FB;
        border: 1px solid #D4E4F3;
        border-radius: 12px;
        color: #0C58A1;
        display: flex;
        flex: 0 0 40px;
        font-size: 18px;
        height: 40px;
        justify-content: center;
    }

    .ai-chat-topbar h1 {
        font-size: 21px;
        font-weight: 800;
        line-height: 1.15;
        margin: 0;
    }

    .ai-chat-topbar p {
        color: #5A6372;
        font-size: 12px;
        margin: 3px 0 0;
    }

    .ai-chat-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ai-chat-actions .btn {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        gap: 8px;
        font-size: 13px;
        min-height: 38px;
        padding-left: 14px;
        padding-right: 14px;
    }

    .ai-chat-layout {
        background: #FFFFFF;
        border: 1px solid #D9E2EC;
        border-radius: 18px;
        box-shadow: 0 24px 70px rgba(11, 35, 62, 0.12);
        display: grid;
        flex: 1 1 auto;
        grid-template-columns: 248px minmax(0, 1fr);
        height: min(760px, calc(100dvh - 205px));
        min-height: 440px;
        overflow: hidden;
    }

    .ai-chat-history,
    .ai-chat-main {
        background: #FFFFFF;
    }

    .ai-chat-history {
        background: #F5F8FB;
        border-right: 1px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        min-height: 0;
        overflow: hidden;
        padding: 14px 12px;
    }

    .ai-pane-title {
        align-items: center;
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .ai-pane-title span {
        font-size: 14px;
        font-weight: 800;
    }

    .ai-pane-title strong {
        background: #F0F5FB;
        border: 1px solid #D8E6F4;
        border-radius: 999px;
        color: #0C58A1;
        font-size: 12px;
        font-weight: 800;
        padding: 4px 9px;
    }

    .ai-new-chat,
    .ai-history-item,
    .ai-history-delete button {
        color: #151414;
        text-decoration: none;
    }

    .ai-new-chat {
        align-items: center;
        background: #FFFFFF;
        border: 1px solid #D8DDE6;
        border-radius: 12px;
        display: flex;
        gap: 10px;
        font-weight: 800;
        margin-bottom: 12px;
        min-height: 44px;
        padding: 10px 13px;
    }

    .ai-new-chat:hover,
    .ai-new-chat.is-active,
    .ai-history-row:hover,
    .ai-history-row.is-active {
        background: #FFFFFF;
        border-color: #C9D8EA;
        color: #0C58A1;
    }

    .ai-history-row:hover .ai-history-item,
    .ai-history-row.is-active .ai-history-item {
        color: #0C58A1;
    }

    .ai-history-list,
    .ai-recent-list {
        display: grid;
        gap: 8px;
    }

    .ai-history-list {
        min-height: 0;
        overflow-y: auto;
        padding-right: 2px;
    }

    .ai-history-row {
        align-items: stretch;
        border: 1px solid transparent;
        border-radius: 12px;
        display: grid;
        gap: 4px;
        grid-template-columns: minmax(0, 1fr) 34px;
    }

    .ai-history-item {
        border-radius: 9px;
        display: grid;
        gap: 4px;
        min-width: 0;
        padding: 11px 0 11px 12px;
    }

    .ai-history-item span {
        font-size: 13px;
        font-weight: 700;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ai-history-delete {
        align-self: center;
        margin: 0;
        padding-right: 5px;
    }

    .ai-history-delete button {
        align-items: center;
        background: transparent;
        border: 0;
        border-radius: 999px;
        color: #94A3B8;
        display: flex;
        height: 30px;
        justify-content: center;
        padding: 0;
        transition: background-color 160ms ease, color 160ms ease;
        width: 30px;
    }

    .ai-history-delete button:hover,
    .ai-history-delete button:focus-visible {
        background: #FEF2F2;
        color: #C1121F;
        outline: none;
    }

    .ai-history-item time,
    .ai-recent-item time,
    .ai-empty-small {
        color: #6B7280;
        font-size: 12px;
    }

    .ai-chat-main {
        display: grid;
        grid-template-rows: auto minmax(0, 1fr) auto;
        height: 100%;
        min-height: 0;
        overflow: hidden;
    }

    .ai-chat-header {
        align-items: center;
        background: rgba(255, 255, 255, 0.92);
        border-bottom: 1px solid #EEF2F6;
        display: flex;
        justify-content: space-between;
        gap: 14px;
        min-height: 56px;
        padding: 10px 18px;
    }

    .ai-chat-header p {
        align-items: center;
        color: #697689;
        display: flex;
        font-size: 11px;
        gap: 7px;
        margin: 3px 0 0;
    }

    .ai-chat-header h2 {
        font-size: 15px;
        font-weight: 800;
        margin: 0;
    }

    .ai-online-dot {
        background: #16A36A;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(22, 163, 106, .12);
        display: inline-block;
        height: 7px;
        width: 7px;
    }

    .ai-messages {
        background:
            radial-gradient(circle, rgba(12, 88, 161, .055) 1px, transparent 1px),
            #F8FAFC;
        background-size: 24px 24px;
        min-height: 0;
        overscroll-behavior: contain;
        overflow-y: auto;
        padding: 20px 24px;
        scroll-behavior: smooth;
        scrollbar-gutter: stable;
    }

    .ai-welcome {
        display: grid;
        margin: 0 auto;
        max-width: 420px;
        min-height: 100%;
        place-items: center;
        text-align: center;
    }

    .ai-welcome-mark {
        align-items: center;
        background: #0C58A1;
        border-radius: 14px;
        color: #fff;
        display: flex;
        font-size: 21px;
        height: 50px;
        justify-content: center;
        margin: 0 auto 12px;
        width: 50px;
    }

    .ai-welcome h2 {
        font-size: 20px;
        font-weight: 800;
        margin: 0 0 5px;
    }

    .ai-welcome p {
        color: #5A6372;
        font-size: 13px;
        margin: 0;
    }

    .ai-message {
        align-items: flex-start;
        display: flex;
        gap: 10px;
        margin: 0 auto 16px;
        max-width: 900px;
        width: 100%;
    }

    .ai-message--user {
        flex-direction: row-reverse;
    }

    .ai-message-avatar {
        align-items: center;
        background: #EDF2F7;
        border: 1px solid #D8DDE6;
        border-radius: 999px;
        color: #0C58A1;
        display: flex;
        flex: 0 0 32px;
        font-size: 12px;
        font-weight: 800;
        height: 32px;
        justify-content: center;
        width: 32px;
    }

    .ai-message--assistant .ai-message-avatar {
        background: #0C58A1;
        border-color: #0C58A1;
        color: #fff;
    }

    .ai-message-body {
        display: flex;
        flex: 0 1 auto;
        flex-direction: column;
        max-width: min(680px, 78%);
        min-width: 0;
        width: auto;
    }

    .ai-message--user .ai-message-body {
        align-items: flex-end;
        margin-left: auto;
        max-width: min(520px, 68%);
    }

    .ai-message-meta {
        align-items: center;
        color: #6B7280;
        display: flex;
        font-size: 10px;
        gap: 7px;
        margin-bottom: 5px;
    }

    .ai-message-bubble {
        align-self: flex-start;
        background: #FFFFFF;
        border: 1px solid #DEE7F0;
        border-radius: 15px;
        box-shadow: 0 5px 16px rgba(15, 35, 58, 0.05);
        color: #1F2A3A;
        display: inline-block;
        font-size: 14px;
        line-height: 1.55;
        max-width: 100%;
        padding: 10px 13px;
        width: fit-content;
        word-break: break-word;
    }

    .ai-message--user .ai-message-bubble {
        align-self: flex-end;
        background: #0B5DA8;
        border-color: #0B5DA8;
        border-radius: 15px 15px 4px 15px;
        box-shadow: 0 7px 18px rgba(12, 88, 161, 0.14);
        color: #fff;
        max-width: 520px;
        white-space: normal;
    }

    .ai-message--assistant .ai-message-bubble {
        border-radius: 15px 15px 15px 4px;
        white-space: normal;
    }

    .ai-message-image {
        align-self: flex-start;
        background: #fff;
        border: 1px solid #D8E3EE;
        border-radius: 15px;
        color: #344054;
        display: block;
        margin-bottom: 7px;
        max-width: min(420px, 100%);
        overflow: hidden;
        position: relative;
        text-decoration: none;
    }

    .ai-message--user .ai-message-image {
        align-self: flex-end;
    }

    .ai-message-image img {
        display: block;
        max-height: 320px;
        object-fit: contain;
        width: 100%;
    }

    .ai-message-image span {
        align-items: center;
        background: rgba(8, 27, 48, .82);
        border-radius: 9px;
        bottom: 9px;
        color: #fff;
        display: flex;
        font-size: 10px;
        font-weight: 700;
        gap: 6px;
        padding: 6px 9px;
        position: absolute;
        right: 9px;
    }

    .ai-message-markdown p,
    .ai-message-markdown ul,
    .ai-message-markdown ol {
        margin: 0 0 9px;
    }

    .ai-message-markdown p:last-child,
    .ai-message-markdown ul:last-child,
    .ai-message-markdown ol:last-child {
        margin-bottom: 0;
    }

    .ai-message-markdown ul,
    .ai-message-markdown ol {
        padding-left: 20px;
    }

    .ai-message-markdown ul {
        list-style: disc;
    }

    .ai-message-markdown ol {
        list-style: decimal;
    }

    .ai-message-markdown strong {
        font-weight: 800;
    }

    .ai-message--pending .ai-message-bubble {
        align-items: center;
        display: inline-flex;
        gap: 10px;
        min-height: 40px;
    }

    .ai-thinking-dots {
        align-items: center;
        display: inline-flex;
        gap: 4px;
    }

    .ai-thinking-dots span {
        animation: aiThinkingPulse 900ms ease-in-out infinite;
        background: #0C58A1;
        border-radius: 50%;
        display: block;
        height: 7px;
        opacity: 0.38;
        width: 7px;
    }

    .ai-thinking-dots span:nth-child(2) {
        animation-delay: 140ms;
    }

    .ai-thinking-dots span:nth-child(3) {
        animation-delay: 280ms;
    }

    @keyframes aiThinkingPulse {
        0%, 100% {
            opacity: 0.3;
            transform: translateY(0);
        }
        50% {
            opacity: 1;
            transform: translateY(-3px);
        }
    }

    .ai-composer {
        align-items: flex-end;
        background: rgba(255, 255, 255, .96);
        border-top: 1px solid #EEF2F6;
        display: grid;
        gap: 12px;
        grid-template-columns: 40px minmax(0, 1fr) 44px;
        padding: 10px 18px 12px;
    }

    .ai-image-preview {
        align-items: center;
        background: #EEF5FB;
        border: 1px solid #D4E4F3;
        border-radius: 13px;
        display: grid;
        gap: 11px;
        grid-column: 1 / -1;
        grid-template-columns: 52px minmax(0, 1fr) 32px;
        padding: 8px;
    }

    .ai-image-preview[hidden] {
        display: none;
    }

    .ai-image-preview img {
        background: #fff;
        border-radius: 9px;
        height: 52px;
        object-fit: cover;
        width: 52px;
    }

    .ai-image-preview strong,
    .ai-image-preview span {
        display: block;
    }

    .ai-image-preview strong {
        color: #1D2939;
        font-size: 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ai-image-preview span {
        color: #5E6D7E;
        font-size: 10px;
        margin-top: 3px;
    }

    .ai-image-preview button {
        background: transparent;
        border: 0;
        border-radius: 8px;
        color: #59687A;
        height: 32px;
        padding: 0;
        width: 32px;
    }

    .ai-attach-button {
        align-items: center;
        align-self: center;
        border-radius: 11px;
        color: #0C58A1;
        cursor: pointer;
        display: flex;
        font-size: 19px;
        height: 40px;
        justify-content: center;
        transition: background-color 150ms ease, transform 150ms cubic-bezier(.22, 1, .36, 1);
        width: 40px;
    }

    .ai-attach-button:hover {
        background: #EAF2FA;
    }

    .ai-attach-button:active {
        transform: scale(.96);
    }

    .ai-attach-button:focus-within {
        outline: 3px solid rgba(12, 88, 161, .16);
    }

    .ai-composer textarea {
        background: #F8FAFC;
        border: 1px solid #CFDAE6;
        border-radius: 14px;
        box-shadow: none;
        font-size: 13px;
        max-height: 160px;
        min-height: 44px;
        padding: 11px 14px;
        resize: none;
    }

    .ai-composer textarea:focus {
        border-color: #0C58A1;
        box-shadow: 0 0 0 3px rgba(12, 88, 161, 0.12);
        outline: none;
    }

    .ai-composer button {
        align-items: center;
        background: #FB0D1B;
        border: 0;
        border-radius: 999px;
        color: #fff;
        display: flex;
        height: 44px;
        justify-content: center;
        transition: background-color 150ms ease, transform 150ms cubic-bezier(.22, 1, .36, 1);
        width: 44px;
    }

    .ai-composer button:hover { background: #D90916; }
    .ai-composer button:active { transform: scale(.96); }

    .ai-composer button:disabled {
        cursor: wait;
        opacity: 0.72;
    }

    .ai-composer.is-sending button i {
        animation: aiSendSpin 900ms linear infinite;
    }

    @keyframes aiSendSpin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 1180px) {
        .ai-chat-layout {
            grid-template-columns: 232px minmax(0, 1fr);
        }
    }

    @media (max-width: 860px) {
        .ai-chat-page {
            min-height: 0;
            padding: 10px 0 22px;
        }

        .ai-chat-shell {
            width: min(100% - 20px, 1224px);
        }

        .ai-chat-topbar {
            align-items: center;
            flex-direction: row;
        }

        .ai-chat-layout {
            grid-template-columns: 1fr;
            grid-template-rows: auto minmax(0, 1fr);
            height: calc(100dvh - 154px);
            min-height: 500px;
        }

        .ai-chat-history {
            border-bottom: 1px solid #E2E8F0;
            border-right: 0;
            max-height: 176px;
        }

        .ai-chat-main {
            grid-template-rows: auto minmax(0, 1fr) auto;
        }

        .ai-chat-header {
            padding: 14px 16px;
        }

        .ai-messages {
            padding: 20px 16px;
        }

        .ai-message-body {
            max-width: 86%;
        }

        .ai-message--user .ai-message-body {
            max-width: 82%;
        }

        .ai-composer {
            padding: 10px 14px 12px;
        }
    }

    @media (max-width: 575px) {
        .ai-chat-shell {
            width: min(100% - 12px, 1224px);
        }

        .ai-chat-topbar p {
            max-width: 210px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ai-chat-actions .btn {
            min-height: 36px;
            padding-left: 11px;
            padding-right: 11px;
        }

        .ai-chat-actions .btn:first-child {
            font-size: 0;
            gap: 0;
            width: 38px;
        }

        .ai-chat-actions .btn:first-child i {
            font-size: 14px;
        }

        .ai-chat-layout {
            min-height: 460px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .ai-thinking-dots span,
        .ai-composer.is-sending button i {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        const messages = document.getElementById('aiMessages');
        const input = document.getElementById('input');
        const composer = document.getElementById('aiComposer');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('aiImagePreview');
        const imagePreviewImg = document.getElementById('aiImagePreviewImg');
        const imagePreviewName = document.getElementById('aiImagePreviewName');
        const imageRemove = document.getElementById('aiImageRemove');
        const chatLayout = document.querySelector('.ai-chat-layout');
        let previewObjectUrl = null;

        const fitChatToViewport = function () {
            if (!chatLayout || window.innerWidth <= 860) {
                if (chatLayout) chatLayout.style.removeProperty('height');
                return;
            }

            const availableHeight = Math.floor(window.innerHeight - chatLayout.getBoundingClientRect().top - 14);
            chatLayout.style.height = Math.max(440, Math.min(760, availableHeight)) + 'px';
        };

        fitChatToViewport();
        window.addEventListener('resize', fitChatToViewport, { passive: true });

        const scrollMessagesToBottom = function () {
            if (!messages) {
                return;
            }

            messages.scrollTop = messages.scrollHeight;
        };

        if (messages) {
            requestAnimationFrame(scrollMessagesToBottom);
            setTimeout(scrollMessagesToBottom, 120);
        }

        if (input) {
            const resize = function () {
                input.style.height = 'auto';
                input.style.height = Math.min(input.scrollHeight, 160) + 'px';
            };

            input.addEventListener('input', resize);
            resize();

            @if(!$activeChat)
                requestAnimationFrame(function () {
                    input.focus({ preventScroll: true });
                });
            @endif
        }

        const clearSelectedImage = function () {
            if (previewObjectUrl) {
                URL.revokeObjectURL(previewObjectUrl);
                previewObjectUrl = null;
            }
            if (imageInput) imageInput.value = '';
            if (imagePreviewImg) imagePreviewImg.removeAttribute('src');
            if (imagePreview) imagePreview.hidden = true;
        };

        if (imageInput) {
            imageInput.addEventListener('change', function () {
                const file = imageInput.files && imageInput.files[0];
                if (!file) {
                    clearSelectedImage();
                    return;
                }

                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    alert('Usá una imagen JPG, PNG o WebP.');
                    clearSelectedImage();
                    return;
                }

                if (file.size > 6 * 1024 * 1024) {
                    alert('La imagen no puede superar los 6 MB.');
                    clearSelectedImage();
                    return;
                }

                if (previewObjectUrl) URL.revokeObjectURL(previewObjectUrl);
                previewObjectUrl = URL.createObjectURL(file);
                imagePreviewImg.src = previewObjectUrl;
                imagePreviewName.textContent = file.name;
                imagePreview.hidden = false;
                input?.focus();
            });
        }

        imageRemove?.addEventListener('click', clearSelectedImage);

        if (composer && messages && input) {
            composer.addEventListener('submit', function () {
                const text = input.value.trim();
                const imageFile = imageInput?.files?.[0] || null;
                const button = composer.querySelector('button[type="submit"]');

                if ((!text && !imageFile) || composer.classList.contains('is-sending')) {
                    if (!text && !imageFile) input.focus();
                    return;
                }

                composer.classList.add('is-sending');

                const previousSubmitInput = composer.querySelector('[data-ai-submit-input="true"]');
                if (previousSubmitInput) {
                    previousSubmitInput.remove();
                }

                const submitInput = document.createElement('input');
                submitInput.type = 'hidden';
                submitInput.name = 'input';
                submitInput.value = text;
                submitInput.dataset.aiSubmitInput = 'true';
                composer.appendChild(submitInput);

                input.removeAttribute('name');
                input.value = '';
                input.style.height = '44px';

                if (button) {
                    button.disabled = true;
                    button.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
                }

                const welcome = messages.querySelector('.ai-welcome');
                if (welcome) {
                    welcome.remove();
                }

                messages.appendChild(createMessage(
                    'user',
                    'Vos',
                    text,
                    imageFile ? URL.createObjectURL(imageFile) : null
                ));
                messages.appendChild(createThinkingMessage());
                requestAnimationFrame(scrollMessagesToBottom);
            });
        }

        function createMessage(role, label, content, imageUrl = null) {
            const article = document.createElement('article');
            article.className = 'ai-message ai-message--' + role;

            const avatar = document.createElement('div');
            avatar.className = 'ai-message-avatar';
            avatar.textContent = role === 'user' ? '{{ Str::upper(Str::substr($cliente->name, 0, 1)) }}' : 'AIQ';

            const body = document.createElement('div');
            body.className = 'ai-message-body';

            const meta = document.createElement('div');
            meta.className = 'ai-message-meta';

            const strong = document.createElement('strong');
            strong.textContent = label;

            const time = document.createElement('time');
            time.textContent = 'Enviando';

            const bubble = document.createElement('div');
            bubble.className = 'ai-message-bubble';
            String(content || '').split(/\r?\n/).forEach(function (line, index) {
                if (index > 0) bubble.appendChild(document.createElement('br'));
                bubble.appendChild(document.createTextNode(line));
            });

            meta.appendChild(strong);
            meta.appendChild(time);
            body.appendChild(meta);
            if (imageUrl) {
                const imageLink = document.createElement('a');
                imageLink.className = 'ai-message-image';
                imageLink.href = imageUrl;
                imageLink.target = '_blank';
                imageLink.rel = 'noopener';

                const image = document.createElement('img');
                image.src = imageUrl;
                image.alt = 'Imagen adjunta';
                imageLink.appendChild(image);
                body.appendChild(imageLink);
            }
            if (content) body.appendChild(bubble);
            article.appendChild(avatar);
            article.appendChild(body);

            return article;
        }

        function createThinkingMessage() {
            const article = document.createElement('article');
            article.className = 'ai-message ai-message--assistant ai-message--pending';
            article.innerHTML = [
                '<div class="ai-message-avatar"><i class="bi bi-stars"></i></div>',
                '<div class="ai-message-body">',
                    '<div class="ai-message-meta"><strong>AIQ</strong><time>pensando</time></div>',
                    '<div class="ai-message-bubble">',
                        '<span>Analizando consulta</span>',
                        '<span class="ai-thinking-dots" aria-hidden="true"><span></span><span></span><span></span></span>',
                    '</div>',
                '</div>',
            ].join('');

            return article;
        }
    });
</script>
@endsection
