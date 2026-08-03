<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Imports\ClientesImport;
use App\Models\AiIntegrationSetting;
use App\Models\AiKnowledgeDocument;
use App\Models\ClienteAiChat;
use App\Models\ClienteAiRequest;
use App\Models\Logincliente;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ClienteZonaController extends Controller
{
    public function index(): View
    {
        $clientes = Logincliente::withCount(['importedClientes', 'aiRequests'])
            ->latest()
            ->paginate(20);

        return view('admin.clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $cliente = new Logincliente($data);

        $this->applyAccess($cliente, $request);
        $cliente->save();

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Logincliente $cliente): View
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Logincliente $cliente): RedirectResponse
    {
        $data = $this->validatedData($request, $cliente);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $cliente->fill($data);
        $this->applyAccess($cliente, $request);
        $cliente->save();

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Logincliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with('danger', 'Cliente eliminado correctamente.');
    }

    public function imports(Logincliente $cliente): View
    {
        $importados = $cliente->importedClientes()->latest()->paginate(50);

        return view('admin.clientes.imports', compact('cliente', 'importados'));
    }

    public function importClientes(Request $request, Logincliente $cliente): RedirectResponse
    {
        $request->validate([
            'archivo' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'],
        ]);

        $file = $request->file('archivo');
        $import = new ClientesImport($cliente, $file->getClientOriginalName());

        Excel::import($import, $file);

        return back()->with('success', 'Importación finalizada. Filas cargadas: '.$import->getImportedCount().'.');
    }

    public function aiRequests(Request $request): View
    {
        $periodStart = now()->subDays(13)->startOfDay();
        $previousPeriodStart = $periodStart->copy()->subDays(14);

        $totalRequests = ClienteAiRequest::count();
        $periodRequests = ClienteAiRequest::where('created_at', '>=', $periodStart)->count();
        $previousPeriodRequests = ClienteAiRequest::whereBetween('created_at', [
            $previousPeriodStart,
            $periodStart->copy()->subSecond(),
        ])->count();
        $periodChange = $previousPeriodRequests > 0
            ? round((($periodRequests - $previousPeriodRequests) / $previousPeriodRequests) * 100)
            : ($periodRequests > 0 ? 100 : 0);

        $dailyCounts = ClienteAiRequest::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', $periodStart)
            ->groupBy('day')
            ->pluck('total', 'day');

        $usageSeries = collect(range(0, 13))->map(function (int $offset) use ($periodStart, $dailyCounts) {
            $date = $periodStart->copy()->addDays($offset);

            return [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d/m'),
                'total' => (int) ($dailyCounts[$date->format('Y-m-d')] ?? 0),
            ];
        });

        $maxDailyUsage = max(1, (int) $usageSeries->max('total'));
        $activeClients = ClienteAiRequest::where('created_at', '>=', $periodStart)
            ->distinct('logincliente_id')
            ->count('logincliente_id');
        $totalChats = ClienteAiChat::count();
        $hiddenChats = ClienteAiChat::whereNotNull('hidden_from_client_at')->count();
        $completedRequests = ClienteAiRequest::where('status', 'completed')->count();
        $successRate = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100, 1) : 0;

        $topClients = Logincliente::query()
            ->whereHas('aiRequests', fn ($query) => $query->where('created_at', '>=', $periodStart))
            ->withCount([
                'aiRequests as period_requests_count' => fn ($query) => $query->where('created_at', '>=', $periodStart),
                'aiChats',
            ])
            ->orderByDesc('period_requests_count')
            ->limit(5)
            ->get();

        $clientId = $request->integer('cliente');
        $threads = ClienteAiChat::query()
            ->with(['logincliente', 'messages' => fn ($query) => $query->oldest()])
            ->withCount('messages')
            ->when($clientId, fn ($query) => $query->where('logincliente_id', $clientId))
            ->latest('last_message_at')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $clients = Logincliente::query()
            ->whereHas('aiChats')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.clientes.ai', compact(
            'totalRequests',
            'periodRequests',
            'periodChange',
            'usageSeries',
            'maxDailyUsage',
            'activeClients',
            'totalChats',
            'hiddenChats',
            'successRate',
            'topClients',
            'threads',
            'clients',
            'clientId'
        ));
    }

    public function knowledge(): View
    {
        $documents = AiKnowledgeDocument::with('uploader')->latest()->paginate(30);
        $counts = [
            'total' => AiKnowledgeDocument::count(),
            'ready' => AiKnowledgeDocument::where('status', 'ready')->count(),
            'processing' => AiKnowledgeDocument::where('status', 'processing')->count(),
            'uploaded' => AiKnowledgeDocument::where('status', 'uploaded')->count(),
            'error' => AiKnowledgeDocument::where('status', 'error')->count(),
        ];

        return view('admin.clientes.knowledge', compact('documents', 'counts'));
    }

    public function storeKnowledge(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'assistant_type' => ['required', Rule::in(['tecnico'])],
            'title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'documents' => ['required', 'array', 'min:1'],
            'documents.*' => ['required', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $uploaded = 0;

        foreach ($request->file('documents') as $file) {
            $path = $file->store('ai-knowledge/'.$data['assistant_type'], 'public');

            $document = AiKnowledgeDocument::create([
                'assistant_type' => $data['assistant_type'],
                'title' => filled($data['title'] ?? null)
                    ? $data['title']
                    : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize() ?: 0,
                'status' => 'uploaded',
                'notes' => $data['notes'] ?? null,
                'uploaded_by' => Auth::id(),
            ]);

            $this->sendKnowledgeToN8n($document);
            $uploaded++;
        }

        return redirect()
            ->route('admin.clientes.knowledge')
            ->with('success', 'PDFs cargados: '.$uploaded.'. N8N los esta indexando en segundo plano.');
    }

    public function destroyKnowledge(AiKnowledgeDocument $document): RedirectResponse
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('danger', 'Documento eliminado de la base de conocimiento.');
    }

    public function knowledgeCallback(Request $request): JsonResponse
    {
        $expectedToken = AiIntegrationSetting::valueFor('n8n_api_key', config('services.n8n.api_key'));
        $receivedToken = $this->callbackTokenFrom($request);

        if (! $expectedToken || ! $receivedToken || ! hash_equals($expectedToken, $receivedToken)) {
            abort(403);
        }

        $data = $request->validate([
            'document_id' => ['required', 'integer', 'exists:ai_knowledge_documents,id'],
            'status' => ['required', 'string', 'max:50'],
            'n8n_document_id' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $status = match (strtolower($data['status'])) {
            'ready', 'success', 'completed', 'complete', 'indexed' => 'ready',
            'error', 'failed', 'failure' => 'error',
            'processing', 'queued', 'accepted' => 'processing',
            default => null,
        };

        if (! $status) {
            throw ValidationException::withMessages([
                'status' => 'El estado recibido desde N8N no es valido.',
            ]);
        }

        $document = AiKnowledgeDocument::findOrFail($data['document_id']);
        $rawResponse = $request->except(['callback', 'callback_token', 'gemini', 'api_key']);

        $document->update([
            'status' => $status,
            'n8n_document_id' => $data['n8n_document_id'] ?? $document->n8n_document_id,
            'raw_response' => $rawResponse,
            'processed_at' => in_array($status, ['ready', 'error'], true) ? now() : null,
        ]);

        return response()->json([
            'ok' => true,
            'document_id' => $document->id,
            'status' => $document->status,
        ]);
    }

    private function validatedData(Request $request, ?Logincliente $cliente = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('loginclientes', 'email')->ignore($cliente?->id),
            ],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => [$cliente ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'access_status' => ['required', Rule::in(['pending', 'active_unlimited', 'active_limited', 'disabled'])],
            'access_days' => ['nullable', 'integer', 'min:1', 'max:3650'],
            'access_expires_at' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        return collect($validated)
            ->only(['name', 'email', 'company', 'phone', 'password'])
            ->filter(fn ($value) => filled($value))
            ->toArray();
    }

    private function applyAccess(Logincliente $cliente, Request $request): void
    {
        $status = $request->input('access_status', 'pending');

        $cliente->is_enabled = in_array($status, ['active_unlimited', 'active_limited'], true);
        $cliente->access_unlimited = $status === 'active_unlimited';

        if ($status === 'active_limited') {
            if ($request->filled('access_days')) {
                $cliente->access_expires_at = now()->addDays((int) $request->input('access_days'))->endOfDay();
            } elseif ($request->filled('access_expires_at')) {
                $cliente->access_expires_at = Carbon::parse($request->input('access_expires_at'))->endOfDay();
            } else {
                throw ValidationException::withMessages([
                    'access_expires_at' => 'Elegí una fecha de vencimiento o una cantidad de días.',
                ]);
            }
        } else {
            $cliente->access_expires_at = null;
        }

        if ($cliente->is_enabled && ! $cliente->approved_at) {
            $cliente->approved_at = now();
        }

        if ($cliente->is_enabled) {
            // Reactivating access always restarts the inactivity clock.
            $cliente->inactive_since_at = null;
        } elseif (! $cliente->inactive_since_at) {
            $cliente->inactive_since_at = now();
        }
    }

    private function sendKnowledgeToN8n(AiKnowledgeDocument $document): void
    {
        $webhookUrl = AiIntegrationSetting::valueFor('n8n_knowledge_webhook_url', config('services.n8n.knowledge_webhook'));

        if (! $webhookUrl) {
            return;
        }

        $document->update(['status' => 'processing']);

        try {
            $n8nApiKey = AiIntegrationSetting::valueFor('n8n_api_key', config('services.n8n.api_key'));
            $callbackUrl = url('/api/n8n/knowledge-callback');

            if (! $n8nApiKey) {
                $document->update([
                    'status' => 'error',
                    'raw_response' => ['error' => 'Falta configurar la N8N API key para autenticar el callback.'],
                    'processed_at' => now(),
                ]);

                return;
            }

            $payload = [
                'document_id' => $document->id,
                'assistant_type' => $document->assistant_type,
                'title' => $document->title,
                'url' => $document->public_url,
                'pdf_url' => $document->public_url,
                'file_url' => $document->public_url,
                'download_url' => $document->public_url,
                'original_name' => $document->original_name,
                'notes' => $document->notes,
                'callback_url' => $callbackUrl,
                'callback_token' => $n8nApiKey,
                'callback' => [
                    'url' => $callbackUrl,
                    'token' => $n8nApiKey,
                ],
                'instruction' => 'Indexar este PDF como fuente exclusiva para el asistente AIQ indicado. Las respuestas a clientes deben limitarse a estos documentos.',
            ];

            $gemini = $this->geminiPayload();
            if ($gemini !== []) {
                $payload['gemini'] = $gemini;
            }

            $http = Http::timeout(12)->connectTimeout(5)->acceptJson();

            if ($n8nApiKey) {
                $http = $http->withToken($n8nApiKey);
            }

            $response = $http->post($webhookUrl, $payload);

            $json = $response->json();

            if (! $response->successful()) {
                $document->update([
                    'status' => 'error',
                    'raw_response' => is_array($json) ? $json : ['body' => $response->body()],
                    'processed_at' => now(),
                ]);

                return;
            }

            $n8nStatus = is_array($json) ? strtolower((string) ($json['status'] ?? 'processing')) : 'processing';
            $status = match ($n8nStatus) {
                'ready', 'success', 'completed', 'complete', 'indexed' => 'ready',
                'error', 'failed', 'failure' => 'error',
                default => 'processing',
            };

            $document->update([
                'status' => $status,
                'n8n_document_id' => is_array($json) ? ($json['document_id'] ?? $json['id'] ?? null) : null,
                'raw_response' => is_array($json) ? $json : ['body' => $response->body()],
                'processed_at' => in_array($status, ['ready', 'error'], true) ? now() : null,
            ]);
        } catch (ConnectionException $exception) {
            $timedOut = str_contains(strtolower($exception->getMessage()), 'timed out')
                || str_contains($exception->getMessage(), 'cURL error 28');

            $document->update([
                'status' => $timedOut ? 'processing' : 'error',
                'raw_response' => ['error' => $exception->getMessage()],
                'processed_at' => $timedOut ? null : now(),
            ]);
        } catch (\Throwable $exception) {
            $document->update([
                'status' => 'error',
                'raw_response' => ['error' => $exception->getMessage()],
                'processed_at' => now(),
            ]);
        }
    }

    private function callbackTokenFrom(Request $request): ?string
    {
        $tokens = [
            $request->bearerToken(),
            $request->input('callback_token'),
            data_get($request->input('callback'), 'token'),
            $request->input('api_key'),
        ];

        foreach ($tokens as $token) {
            if (! is_string($token)) {
                continue;
            }

            $token = trim($token);

            if ($token !== '' && strtolower($token) !== 'null') {
                return $token;
            }
        }

        return null;
    }

    private function geminiPayload(): array
    {
        $payload = [];
        $model = AiIntegrationSetting::valueFor('gemini_model', config('services.gemini.model'));
        $apiKey = AiIntegrationSetting::valueFor('gemini_api_key', config('services.gemini.api_key'));

        if ($model) {
            $payload['model'] = $model;
        }

        if ($apiKey) {
            $payload['api_key'] = $apiKey;
        }

        return $payload;
    }
}
