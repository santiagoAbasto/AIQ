<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AiIntegrationSetting;
use App\Support\AiqAssistantInstructions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AiIntegrationController extends Controller
{
    private const SECRET_FIELDS = [
        'gemini_api_key' => 'gemini_api_key',
        'n8n_api_key' => 'n8n_api_key',
        'n8n_technical_webhook_url' => 'n8n_technical_webhook_url',
        'n8n_commercial_webhook_url' => 'n8n_commercial_webhook_url',
        'n8n_knowledge_webhook_url' => 'n8n_knowledge_webhook_url',
    ];

    public function edit(): View
    {
        $this->authorizeAdmin();

        return view('admin.integrations.edit', [
            'geminiModel' => AiIntegrationSetting::valueFor('gemini_model', config('services.gemini.model')),
            'technicalAssistantInstructions' => AiIntegrationSetting::valueFor(
                AiqAssistantInstructions::SETTING_KEY,
                AiqAssistantInstructions::default()
            ),
            'commercialWhatsappContacts' => AiIntegrationSetting::valueFor(
                AiqAssistantInstructions::COMMERCIAL_CONTACTS_SETTING_KEY,
                AiqAssistantInstructions::defaultCommercialContacts()
            ),
            'status' => $this->status(),
            'updatedBy' => AiIntegrationSetting::query()
                ->with('updater')
                ->latest('updated_at')
                ->first(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'gemini_model' => ['nullable', 'string', 'max:120', 'regex:/^[A-Za-z0-9._\/:-]+$/'],
            'gemini_api_key' => ['nullable', 'string', 'max:1000'],
            'n8n_api_key' => ['nullable', 'string', 'max:1000'],
            'n8n_technical_webhook_url' => ['nullable', 'url', 'max:2048'],
            'n8n_commercial_webhook_url' => ['nullable', 'url', 'max:2048'],
            'n8n_knowledge_webhook_url' => ['nullable', 'url', 'max:2048'],
            'technical_assistant_instructions' => ['nullable', 'string', 'max:8000'],
            'commercial_whatsapp_contacts' => ['nullable', 'string', 'max:4000'],
            'clear_secrets' => ['nullable', 'array'],
            'clear_secrets.*' => ['string', Rule::in(array_keys(self::SECRET_FIELDS))],
        ]);

        $userId = Auth::id();

        if (filled($data['gemini_model'] ?? null)) {
            AiIntegrationSetting::putValue('gemini_model', $data['gemini_model'], false, $userId);
        } else {
            AiIntegrationSetting::forgetKey('gemini_model');
        }

        if (filled($data['technical_assistant_instructions'] ?? null)) {
            AiIntegrationSetting::putValue(
                AiqAssistantInstructions::SETTING_KEY,
                $data['technical_assistant_instructions'],
                false,
                $userId
            );
        } else {
            AiIntegrationSetting::forgetKey(AiqAssistantInstructions::SETTING_KEY);
        }

        if (filled($data['commercial_whatsapp_contacts'] ?? null)) {
            AiIntegrationSetting::putValue(
                AiqAssistantInstructions::COMMERCIAL_CONTACTS_SETTING_KEY,
                $data['commercial_whatsapp_contacts'],
                false,
                $userId
            );
        } else {
            AiIntegrationSetting::forgetKey(AiqAssistantInstructions::COMMERCIAL_CONTACTS_SETTING_KEY);
        }

        foreach (self::SECRET_FIELDS as $input => $settingKey) {
            if ($request->filled($input)) {
                AiIntegrationSetting::putValue($settingKey, $request->input($input), true, $userId);
            }
        }

        foreach ($data['clear_secrets'] ?? [] as $settingKey) {
            AiIntegrationSetting::forgetKey($settingKey);
        }

        return redirect()
            ->route('admin.integrations.edit')
            ->with('success', 'Integraciones IA actualizadas de forma segura.');
    }

    private function status(): array
    {
        return [
            'gemini_api_key' => AiIntegrationSetting::hasStoredValue('gemini_api_key') || filled(config('services.gemini.api_key')),
            'n8n_api_key' => AiIntegrationSetting::hasStoredValue('n8n_api_key') || filled(config('services.n8n.api_key')),
            'n8n_technical_webhook_url' => AiIntegrationSetting::hasStoredValue('n8n_technical_webhook_url') || filled(config('services.n8n.technical_webhook')),
            'n8n_commercial_webhook_url' => AiIntegrationSetting::hasStoredValue('n8n_commercial_webhook_url') || filled(config('services.n8n.commercial_webhook')),
            'n8n_knowledge_webhook_url' => AiIntegrationSetting::hasStoredValue('n8n_knowledge_webhook_url') || filled(config('services.n8n.knowledge_webhook')),
        ];
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'Administrador', 403);
    }
}
