@extends('admin.layouts.master')

@section('title', 'Integraciones IA')

@section('content')
<div class="admin-page-hero">
    <div>
        <p class="admin-page-kicker">Seguridad · IA · N8N</p>
        <h1 class="admin-page-title">Integraciones IA</h1>
        <p class="admin-page-description">
            Guardá las credenciales de Gemini y N8N cifradas. Las claves nunca se muestran de vuelta y solo se usan desde el servidor.
        </p>
    </div>
    <a href="{{ route('admin.clientes.knowledge') }}" class="btn btn-outline-primary">Base PDF</a>
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

<div class="row g-4 mb-4">
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-stars"></i></div>
                <h2>{{ $status['gemini_api_key'] ? 'OK' : '-' }}</h2>
                <p>Gemini API key</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-diagram-3"></i></div>
                <h2>{{ $status['n8n_api_key'] ? 'OK' : '-' }}</h2>
                <p>N8N API key</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-chat-square-text"></i></div>
                <h2>{{ $status['n8n_technical_webhook_url'] ? 'OK' : '-' }}</h2>
                <p>Webhook asesor AIQ</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                <h2>{{ $status['n8n_knowledge_webhook_url'] ? 'OK' : '-' }}</h2>
                <p>Webhook PDFs</p>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.integrations.update') }}" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header">Gemini</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="gemini_model">Modelo</label>
                        <input
                            class="form-control"
                            id="gemini_model"
                            name="gemini_model"
                            type="text"
                            value="{{ old('gemini_model', $geminiModel) }}"
                            placeholder="Pegá el ID exacto del modelo"
                            autocomplete="off"
                            spellcheck="false">
                        <small class="text-muted">Ejemplo: el modelo activo que tengas habilitado en Google AI Studio.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="gemini_api_key">Gemini API key</label>
                        <input
                            class="form-control"
                            id="gemini_api_key"
                            name="gemini_api_key"
                            type="password"
                            placeholder="{{ $status['gemini_api_key'] ? 'Clave configurada: dejar vacío para conservar' : 'Pegar nueva clave' }}"
                            autocomplete="new-password"
                            spellcheck="false"
                            data-lpignore="true">
                        <small class="text-muted">No se imprime en HTML ni se muestra en el panel.</small>
                    </div>

                    @if($status['gemini_api_key'])
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="gemini_api_key" id="clear_gemini_api_key" name="clear_secrets[]">
                            <label class="form-check-label" for="clear_gemini_api_key">Eliminar Gemini API key guardada</label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header">N8N</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="n8n_api_key">N8N API key</label>
                        <input
                            class="form-control"
                            id="n8n_api_key"
                            name="n8n_api_key"
                            type="password"
                            placeholder="{{ $status['n8n_api_key'] ? 'Clave configurada: dejar vacío para conservar' : 'Pegar nueva clave' }}"
                            autocomplete="new-password"
                            spellcheck="false"
                            data-lpignore="true">
                    </div>

                    @if($status['n8n_api_key'])
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="n8n_api_key" id="clear_n8n_api_key" name="clear_secrets[]">
                            <label class="form-check-label" for="clear_n8n_api_key">Eliminar N8N API key guardada</label>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="n8n_technical_webhook_url">Webhook asesor AIQ</label>
                        <input
                            class="form-control"
                            id="n8n_technical_webhook_url"
                            name="n8n_technical_webhook_url"
                            type="password"
                            placeholder="{{ $status['n8n_technical_webhook_url'] ? 'Webhook configurado: dejar vacío para conservar' : 'https://...' }}"
                            autocomplete="new-password"
                            spellcheck="false">
                        <small class="text-muted">Usá acá la Production URL del workflow de chat de N8N.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="n8n_knowledge_webhook_url">Webhook base PDF</label>
                        <input
                            class="form-control"
                            id="n8n_knowledge_webhook_url"
                            name="n8n_knowledge_webhook_url"
                            type="password"
                            placeholder="{{ $status['n8n_knowledge_webhook_url'] ? 'Webhook configurado: dejar vacío para conservar' : 'https://...' }}"
                            autocomplete="new-password"
                            spellcheck="false">
                    </div>

                    @foreach([
                        'n8n_technical_webhook_url' => 'Eliminar webhook asesor AIQ',
                        'n8n_knowledge_webhook_url' => 'Eliminar webhook base PDF',
                    ] as $key => $label)
                        @if($status[$key])
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $key }}" id="clear_{{ $key }}" name="clear_secrets[]">
                                <label class="form-check-label" for="clear_{{ $key }}">{{ $label }}</label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Instrucciones del asesor</div>
        <div class="card-body">
            <label class="form-label" for="technical_assistant_instructions">Prompt general para todos los chats</label>
            <textarea
                class="form-control"
                id="technical_assistant_instructions"
                name="technical_assistant_instructions"
                rows="12"
                maxlength="8000"
                spellcheck="true">{{ old('technical_assistant_instructions', $technicalAssistantInstructions) }}</textarea>
            <small class="text-muted d-block mt-2">
                Se envía a N8N como <code>assistant_instructions</code> y <code>system_prompt</code>. Usalo para definir tono, reglas comerciales, datos que debe pedir y derivaciones.
            </small>

            <hr class="my-4">

            <label class="form-label" for="commercial_whatsapp_contacts">WhatsApp comerciales</label>
            <textarea
                class="form-control"
                id="commercial_whatsapp_contacts"
                name="commercial_whatsapp_contacts"
                rows="4"
                maxlength="4000"
                spellcheck="false">{{ old('commercial_whatsapp_contacts', $commercialWhatsappContacts) }}</textarea>
            <small class="text-muted d-block mt-2">
                Un contacto por línea. Formato recomendado: <code>Ventas AIQ | +54 9 11 5185-3393</code>. El asistente solo pasa el enlace cuando el cliente confirma que quiere hablar con un asesor comercial.
            </small>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Confirmación segura</div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-lg-8">
                    <label class="form-label" for="current_password">Contraseña actual del administrador</label>
                    <input
                        class="form-control"
                        id="current_password"
                        name="current_password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Obligatoria para guardar credenciales">
                    <small class="text-muted">Esto evita cambios de claves con una sesión abierta accidentalmente.</small>
                </div>
                <div class="col-12 col-lg-4 d-grid">
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="bi bi-shield-lock me-2"></i>
                        Guardar integraciones
                    </button>
                </div>
            </div>

            @if($updatedBy)
                <small class="text-muted d-block mt-3">
                    Última actualización:
                    {{ $updatedBy->updated_at->format('d/m/Y H:i') }}
                    @if($updatedBy->updater)
                        por {{ $updatedBy->updater->name }}.
                    @endif
                </small>
            @endif
        </div>
    </div>
</form>
@endsection
