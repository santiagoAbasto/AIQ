<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\AiIntegrationSetting;
use App\Models\AiKnowledgeDocument;
use App\Models\ClienteAiChat;
use App\Models\ClienteAiRequest;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Rede;
use App\Support\AiqAssistantInstructions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClienteDashboardController extends Controller
{
    private const DEFAULT_ASSISTANT_TYPE = 'tecnico';

    private const EMPTY_ASSISTANT_MESSAGE = 'El asistente respondió sin contenido.';

    private const ASSISTANT_TEXT_KEYS = [
        'output',
        'response',
        'respuesta',
        'answer',
        'message',
        'text',
        'content',
        'solution',
        'solucion',
        'producto',
    ];

    public function dashboard(): View
    {
        $cliente = Auth::guard('logincliente')->user();
        $assistantTypes = [self::DEFAULT_ASSISTANT_TYPE];

        return view('clientes.dashboard', array_merge($this->layoutData(), [
            'cliente' => $cliente,
            'consultasCount' => $cliente->aiRequests()->count(),
            'ultimasConsultas' => $cliente->aiRequests()->latest()->limit(5)->get(),
            'latestChats' => $cliente->aiChats()
                ->whereIn('assistant_type', $assistantTypes)
                ->whereNull('hidden_from_client_at')
                ->latest('last_message_at')
                ->latest()
                ->limit(4)
                ->get(),
            'chatsCount' => $cliente->aiChats()
                ->whereIn('assistant_type', $assistantTypes)
                ->whereNull('hidden_from_client_at')
                ->count(),
        ]));
    }

    public function advisor(): RedirectResponse
    {
        return redirect()->route('cliente.assistant', self::DEFAULT_ASSISTANT_TYPE);
    }

    public function assistant(Request $request, string $type): View|RedirectResponse
    {
        $meta = $this->assistantMeta($type);
        $cliente = Auth::guard('logincliente')->user();
        $assistantTypes = [self::DEFAULT_ASSISTANT_TYPE];
        $chats = $cliente->aiChats()
            ->whereIn('assistant_type', $assistantTypes)
            ->whereNull('hidden_from_client_at')
            ->latest('last_message_at')
            ->latest()
            ->limit(30)
            ->get();
        $activeChat = null;

        if ($request->filled('chat')) {
            $activeChat = $cliente->aiChats()
                ->whereIn('assistant_type', $assistantTypes)
                ->whereNull('hidden_from_client_at')
                ->whereKey($request->query('chat'))
                ->first();

            if ($activeChat && $activeChat->assistant_type !== $type) {
                return redirect()->route('cliente.assistant', [
                    'type' => $activeChat->assistant_type,
                    'chat' => $activeChat->id,
                ]);
            }
        }

        if (! $request->boolean('new')) {
            $activeChat ??= $chats->first();
        }

        return view('clientes.assistant', array_merge($this->layoutData(), [
            'cliente' => $cliente,
            'type' => $type,
            'meta' => $meta,
            'chats' => $chats,
            'activeChat' => $activeChat,
            'messages' => $activeChat
                ? $this->prepareMessagesForDisplay($activeChat->messages()->oldest()->get())
                : collect(),
            'requests' => $cliente->aiRequests()->where('assistant_type', $type)->latest()->limit(8)->get(),
        ]));
    }

    public function ask(Request $request, string $type): RedirectResponse
    {
        $meta = $this->assistantMeta($type);

        if (trim((string) $request->input('input')) === '' && ! $request->hasFile('image')) {
            return back()
                ->withErrors(['input' => 'ESCRIBI UNA CONSULTA O ADJUNTA UNA IMAGEN.'])
                ->withInput();
        }

        $data = $request->validate([
            'input' => ['nullable', 'string', 'max:4000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:6144'],
            'chat_id' => ['nullable', 'integer'],
        ], [
            'image.image' => 'El archivo debe ser una imagen válida.',
            'image.mimes' => 'La imagen debe estar en formato JPG, PNG o WebP.',
            'image.max' => 'La imagen no puede superar los 6 MB.',
        ]);

        $cliente = Auth::guard('logincliente')->user();
        $input = trim((string) ($data['input'] ?? ''));
        $displayInput = $input !== '' ? $input : 'Analizá esta imagen y describí lo que observás.';
        $webhookUrl = AiIntegrationSetting::valueFor($meta['webhook_setting'], config($meta['config_key']));
        $knowledgeDocuments = AiKnowledgeDocument::where('assistant_type', $type)
            ->whereIn('status', ['uploaded', 'ready'])
            ->latest()
            ->get();
        $status = 'completed';
        $rawResponse = null;
        $chat = $this->resolveChat($cliente, $type, $data['chat_id'] ?? null, $input ?: 'Análisis de imagen');
        $commercialContacts = $this->commercialContacts($cliente, $chat);
        $assistantInstructions = $this->assistantRuntimeInstructions($commercialContacts);
        $imageAttachment = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageAttachment = [
                'path' => $image->store('ai-chat-images/'.$cliente->id.'/'.$chat->id, 'public'),
                'mime' => $image->getMimeType() ?: $image->getClientMimeType(),
                'name' => $image->getClientOriginalName(),
            ];
        }

        $chat->messages()->create([
            'role' => 'user',
            'content' => $input,
            'attachment_path' => $imageAttachment['path'] ?? null,
            'attachment_mime' => $imageAttachment['mime'] ?? null,
            'attachment_name' => $imageAttachment['name'] ?? null,
            'status' => 'sent',
        ]);

        $chat->update(['last_message_at' => now()]);
        $conversationHistory = $this->chatHistory($chat, 60);
        $caseContext = $this->diagnosticCaseContext($chat);
        $gemini = $this->geminiPayload();

        if ($this->requestsRestrictedDocument($displayInput)) {
            $output = 'Las fichas técnicas, hojas de seguridad, certificados y documentos internos no se comparten desde el asistente. Puedo responderte una consulta técnica puntual sobre el producto o ayudarte a contactar a un asesor.';
            $status = 'restricted_document';
            $rawResponse = ['policy' => 'restricted_document_delivery'];
        } elseif ($imageAttachment && ! empty($gemini['api_key'])) {
            [$output, $status, $rawResponse] = $this->askGeminiDirectly(
                $type,
                $meta,
                $displayInput,
                $cliente,
                $chat,
                $knowledgeDocuments,
                $gemini,
                $imageAttachment
            );
        } elseif (! $webhookUrl) {
            if (empty($gemini['api_key'])) {
                $status = 'pending_configuration';
                $output = 'Configurá el webhook de N8N o la API key de Gemini para activar '.$meta['title'].'.';
            } else {
                [$output, $status, $rawResponse] = $this->askGeminiDirectly(
                    $type,
                    $meta,
                    $displayInput,
                    $cliente,
                    $chat,
                    $knowledgeDocuments,
                    $gemini,
                    $imageAttachment
                );
            }
        } else {
            $diagnosticQuery = $this->diagnosticRetrievalQuery($chat, $displayInput);
            $payload = [
                'type' => $type,
                'input' => $displayInput,
                'chatInput' => $displayInput,
                'question' => $displayInput,
                'query' => $diagnosticQuery,
                'message' => $displayInput,
                'retrieval_query' => $diagnosticQuery,
                'sessionId' => 'cliente-'.$cliente->id.'-chat-'.$chat->id,
                'mode' => 'rag_query',
                'scope' => 'Analizá la consulta y, si hay una imagen, inspeccioná proceso probable, pieza, color, textura, defectos y códigos visibles. Contrastá con el conocimiento interno de AIQ. No asegures causas ni fórmulas si la imagen no aporta evidencia suficiente.',
                'instruction' => trim($assistantInstructions."\n\nLa consulta real del cliente está en input/chatInput/question/query/message. Si alguno de esos campos tiene texto, no respondas con un saludo genérico: respondé esa consulta usando la base de conocimiento."),
                'assistant_instructions' => $assistantInstructions,
                'system_prompt' => $assistantInstructions,
                'knowledge' => [
                    'assistant_type' => $type,
                    'vector_store' => 'supabase_pgvector',
                    'table' => 'aiq_pdf_chunks',
                    'document_count' => $knowledgeDocuments->count(),
                    'metadata_filter' => [
                        'assistant_type' => $type,
                    ],
                ],
                'response_contract' => [
                    'format' => 'json',
                    'fields' => ['output', 'response', 'status', 'sources'],
                    'sources' => 'Devuelve solo metadata breve de las fuentes usadas; no devuelvas chunks completos ni PDFs.',
                    'max_sources' => 5,
                ],
                'conversation_history' => $conversationHistory,
                'conversation_history_text' => $this->conversationHistoryText($conversationHistory),
                'case_context' => $caseContext,
                'memory_instruction' => 'Usá el historial completo como memoria persistente del caso. Antes de preguntar un dato, comprobá si ya aparece en conversation_history o case_context. No contradigas ni olvides información confirmada previamente.',
                'chat' => [
                    'id' => $chat->id,
                    'title' => $chat->title,
                    'history' => $conversationHistory,
                    'history_text' => $this->conversationHistoryText($conversationHistory),
                    'case_context' => $caseContext,
                ],
                'cliente' => [
                    'id' => $cliente->id,
                    'name' => $cliente->name,
                    'email' => $cliente->email,
                    'company' => $cliente->company,
                ],
                'commercial_contact' => $commercialContacts[0] ?? null,
                'commercial_contacts' => $commercialContacts,
            ];

            if ($imageAttachment) {
                $imageBytes = Storage::disk('public')->get($imageAttachment['path']);
                $payload['image'] = [
                    'present' => true,
                    'mime_type' => $imageAttachment['mime'],
                    'file_name' => $imageAttachment['name'],
                    'base64' => base64_encode($imageBytes),
                    'data_url' => 'data:'.$imageAttachment['mime'].';base64,'.base64_encode($imageBytes),
                    'analysis_mode' => 'industrial_visual_diagnosis',
                ];
            }

            if ($gemini !== []) {
                $payload['gemini'] = $gemini;
            }

            try {
                $http = Http::timeout(45)->acceptJson();
                $n8nApiKey = AiIntegrationSetting::valueFor('n8n_api_key', config('services.n8n.api_key'));

                if ($n8nApiKey) {
                    $http = $http->withToken($n8nApiKey);
                }

                $response = $http->post($webhookUrl, $payload);
                $rawResponse = $response->json();
            } catch (\Throwable $exception) {
                $status = 'webhook_error';
                $output = 'No se pudo obtener respuesta del asistente. Revisá el flujo de N8N.';
                $rawResponse = ['error' => $exception->getMessage()];
            }

            if (isset($response) && $response->failed()) {
                $status = 'webhook_error';
                $output = 'No se pudo obtener respuesta del asistente. Revisá el flujo de N8N.';
            } elseif (isset($response)) {
                $output = $this->extractOutput($response->json(), $response->body());
            }
        }

        $aiRequest = ClienteAiRequest::create([
            'logincliente_id' => $cliente->id,
            'assistant_type' => $type,
            'input' => $input !== '' ? $input : '[Imagen adjunta para análisis]',
            'output' => $output,
            'status' => $status,
            'webhook_url' => $webhookUrl,
            'raw_response' => $rawResponse,
        ]);

        $chat->messages()->create([
            'cliente_ai_request_id' => $aiRequest->id,
            'role' => 'assistant',
            'content' => $output,
            'status' => $status,
            'raw_response' => $rawResponse,
        ]);

        $chat->update(['last_message_at' => now()]);

        return redirect()
            ->route('cliente.assistant', ['type' => $type, 'chat' => $chat->id]);
    }

    public function destroyChat(Request $request, string $type, ClienteAiChat $chat): RedirectResponse
    {
        $this->assistantMeta($type);

        $cliente = Auth::guard('logincliente')->user();
        $chat = $cliente->aiChats()
            ->where('assistant_type', $type)
            ->whereNull('hidden_from_client_at')
            ->whereKey($chat->id)
            ->firstOrFail();

        $currentChatId = (int) $request->input('current_chat_id');
        $redirectChat = null;

        if ($currentChatId && $currentChatId !== $chat->id) {
            $redirectChat = $cliente->aiChats()
                ->where('assistant_type', $type)
                ->whereNull('hidden_from_client_at')
                ->whereKey($currentChatId)
                ->first();
        }

        $redirectChat ??= $cliente->aiChats()
            ->where('assistant_type', $type)
            ->whereNull('hidden_from_client_at')
            ->where('id', '!=', $chat->id)
            ->latest('last_message_at')
            ->latest()
            ->first();

        $chat->update(['hidden_from_client_at' => now()]);

        $routeParams = ['type' => $type];
        if ($redirectChat) {
            $routeParams['chat'] = $redirectChat->id;
        } else {
            $routeParams['new'] = 1;
        }

        return redirect()
            ->route('cliente.assistant', $routeParams)
            ->with('success', 'Chat eliminado de tu historial.');
    }

    private function askGeminiDirectly(
        string $type,
        array $meta,
        string $input,
        $cliente,
        ClienteAiChat $chat,
        $knowledgeDocuments,
        array $gemini,
        ?array $imageAttachment = null
    ): array {
        $model = $this->normalizeGeminiModel($gemini['model'] ?? null);
        $parts = [
            [
                'text' => $this->geminiPrompt($type, $meta, $cliente, $chat, $knowledgeDocuments),
            ],
        ];

        if ($imageAttachment && Storage::disk('public')->exists($imageAttachment['path'])) {
            $parts[] = [
                'text' => 'Imagen enviada por el cliente. Inspeccionala siguiendo el protocolo de diagnóstico visual industrial.',
            ];
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $imageAttachment['mime'],
                    'data' => base64_encode(Storage::disk('public')->get($imageAttachment['path'])),
                ],
            ];
        }

        $maxPdfBytes = $imageAttachment
            ? 6 * 1024 * 1024
            : 18 * 1024 * 1024;
        $usedPdfBytes = 0;
        $attachedTitles = [];
        $skippedTitles = [];

        foreach ($knowledgeDocuments as $document) {
            if (! Storage::disk('public')->exists($document->file_path)) {
                $skippedTitles[] = $document->title;

                continue;
            }

            $size = Storage::disk('public')->size($document->file_path);
            if (($usedPdfBytes + $size) > $maxPdfBytes) {
                $skippedTitles[] = $document->title;

                continue;
            }

            $usedPdfBytes += $size;
            $attachedTitles[] = $document->title;
            $parts[] = ['text' => 'Documento AIQ: '.$document->title];
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $document->mime_type ?: 'application/pdf',
                    'data' => base64_encode(Storage::disk('public')->get($document->file_path)),
                ],
            ];
        }

        $parts[] = [
            'text' => "Consulta del cliente:\n".$input,
        ];

        if ($skippedTitles !== []) {
            $parts[] = [
                'text' => 'Documentos no adjuntados por tamaño o archivo faltante: '.implode(', ', $skippedTitles).'.',
            ];
        }

        try {
            $response = Http::timeout(90)
                ->acceptJson()
                ->withHeaders(['x-goog-api-key' => $gemini['api_key']])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent',
                    [
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => $parts,
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.2,
                            'topP' => 0.9,
                            'maxOutputTokens' => 1800,
                        ],
                    ]
                );

            $json = $response->json();
            $rawResponse = is_array($json)
                ? array_merge($json, [
                    '_aiq_attached_documents' => $attachedTitles,
                    '_aiq_skipped_documents' => $skippedTitles,
                ])
                : [
                    'body' => $response->body(),
                    '_aiq_attached_documents' => $attachedTitles,
                    '_aiq_skipped_documents' => $skippedTitles,
                ];

            if ($response->failed()) {
                return [
                    'No se pudo generar la respuesta del asistente. Revisá la configuración del modelo en el panel de administración.',
                    'gemini_error',
                    $rawResponse,
                ];
            }

            return [
                $this->extractGeminiOutput($json, $response->body()),
                'completed',
                $rawResponse,
            ];
        } catch (\Throwable $exception) {
            return [
                'No se pudo conectar con el asistente. Revisá la conexión del servidor o configurá N8N como intermediario.',
                'gemini_error',
                ['error' => 'gemini_connection_failed', 'exception' => $exception::class],
            ];
        }
    }

    private function geminiPrompt(string $type, array $meta, $cliente, ClienteAiChat $chat, $knowledgeDocuments): string
    {
        $history = collect($this->chatHistory($chat))
            ->map(fn ($message) => strtoupper($message['role']).': '.$message['content'])
            ->implode("\n\n");

        $documentTitles = $knowledgeDocuments->pluck('title')->implode(', ');

        return trim(implode("\n\n", array_filter([
            'Sos '.$meta['title'].' de AIQ. Respondé en español, con tono claro, técnico y útil.',
            $this->assistantRuntimeInstructions(),
            'Cliente: '.$cliente->name.($cliente->company ? ' · '.$cliente->company : ''),
            'Regla interna: para responder, usá exclusivamente la información interna de AIQ adjunta o listada. Si la respuesta no está respaldada por esa información, pedí los datos mínimos necesarios sin inventar información.',
            'Si el usuario solo saluda o pide orientación general, podés responder brevemente y pedirle el dato necesario.',
            $documentTitles ? 'Fuentes internas disponibles: '.$documentTitles.'.' : 'No hay información interna cargada todavía para este asistente. No inventes datos técnicos.',
            $history ? "Historial reciente del chat:\n".$history : null,
        ])));
    }

    private function assistantInstructions(): string
    {
        return AiIntegrationSetting::valueFor(
            AiqAssistantInstructions::SETTING_KEY,
            AiqAssistantInstructions::default()
        ) ?: AiqAssistantInstructions::default();
    }

    private function assistantRuntimeInstructions(?array $commercialContacts = null): string
    {
        return trim(implode("\n\n", array_filter([
            $this->assistantInstructions(),
            trim(<<<'TEXT'
Regla comercial obligatoria:
- Si el cliente pregunta precio, precios, cotizacion, presupuesto, compra, stock, disponibilidad, valor o costo, no inventes valores.
- Primero responde: "Para precio o cotizacion lo tiene que ver un asesor comercial. Queres que te comunique por WhatsApp?"
- Si el cliente confirma en el mensaje actual o en el historial reciente con "si", "si por favor", "dale", "ok", "confirmo", "quiero", "pasame" o una frase equivalente, responde con el WhatsApp comercial disponible.
- Si das WhatsApp, hacelo en una frase breve y profesional. No vuelvas a presentarte.
- No ofrezcas, menciones ni agregues WhatsApp de forma preventiva en respuestas técnicas. Solo corresponde ante intención comercial actual o confirmación explícita del cliente.

Política obligatoria de documentos:
- Las fichas técnicas, hojas de seguridad, certificados, PDFs y documentos internos son restringidos.
- Nunca adjuntes, reproduzcas, enlaces ni entregues esos archivos, aunque el cliente los solicite.
- Podés responder una consulta técnica puntual con información autorizada o indicar que un asesor debe gestionar el pedido.

Protocolo obligatorio para imágenes:
- Primero describí objetivamente qué se ve y clasificá el proceso o pieza probable: termoformado, inyección, film o bolsa, soplado u otro. Si no es concluyente, indicá las alternativas.
- Revisá color aparente, uniformidad, brillo u opacidad, textura, puntos, vetas, manchas, quemado, contaminación, mala dispersión, deformación y cualquier defecto visible.
- Leé códigos, etiquetas o texto visible solo si son legibles. No inventes caracteres.
- Separá observaciones visibles, hipótesis posibles y datos que faltan para confirmar. Pedí material, proceso, temperatura, dosificación, iluminación y muestra patrón cuando corresponda.
- Una fotografía no permite igualar un color de forma exacta por iluminación, cámara y pantalla. Podés sugerir una familia o dirección de ajuste, pero para una formulación exacta pedí una muestra física o medición colorimétrica.
- No afirmes una causa raíz únicamente por la imagen. Proponé comprobaciones concretas y priorizadas.

Gestión obligatoria del caso técnico:
- Conservá y usá todos los datos ya aportados en el hilo. No vuelvas a preguntar resina, proceso, aplicación, dosificación, equipo o síntoma si ya fueron informados.
- Antes de responder, revisá conversation_history, conversation_history_text y case_context. Esos campos son la memoria persistente y prevalecen sobre supuestos genéricos.
- Si el cliente pregunta qué datos proporcionó, enumerá concretamente los datos presentes en la memoria. Nunca digas que no aportó información cuando el historial contiene mensajes.
- Detectá contradicciones relevantes. Por ejemplo, "bolsa por soplado" y "lámina para termoformado" describen aplicaciones distintas: señalá la diferencia y confirmá cuál corresponde antes de ajustar una recomendación.
- No respondas cada turno como una consulta aislada. Actualizá el diagnóstico con la nueva evidencia.
- Cuando el cliente pida una solución o un aditivo, respondé con: diagnóstico más probable, acciones ordenadas, prueba controlada, criterio para evaluar el resultado y como máximo una pregunta que realmente cambie la decisión.
- Si los documentos AIQ contienen un producto aplicable, recomendalo con su código o nombre y la dosificación documentada. Si no contienen respaldo suficiente, recomendá la función buscada, no inventes códigos ni porcentajes, y ofrecé derivación con un técnico.
- Diferenciá defecto, causa y corrección. Evitá repetir humedad o incompatibilidad si los datos del caso ya las vuelven poco probables, salvo que expliques qué evidencia concreta justificaría revisarlas.
TEXT),
        ])));
    }

    private function commercialContacts($cliente = null, ?ClienteAiChat $chat = null): array
    {
        $contacts = collect(preg_split(
            '/\r\n|\r|\n/',
            (string) AiIntegrationSetting::valueFor(
                AiqAssistantInstructions::COMMERCIAL_CONTACTS_SETTING_KEY,
                AiqAssistantInstructions::defaultCommercialContacts()
            )
        ))
            ->map(fn (string $line, int $index) => $this->parseCommercialContact($line, $index, $cliente, $chat))
            ->filter()
            ->values()
            ->all();

        if ($contacts !== []) {
            return $contacts;
        }

        $defaultContact = $this->parseCommercialContact(
            AiqAssistantInstructions::defaultCommercialContacts(),
            0,
            $cliente,
            $chat
        );

        return $defaultContact ? [$defaultContact] : [];
    }

    private function parseCommercialContact(
        string $line,
        int $index,
        $cliente = null,
        ?ClienteAiChat $chat = null
    ): ?array
    {
        $line = trim($line);

        if ($line === '') {
            return null;
        }

        if (str_contains($line, '|')) {
            [$label, $phone] = array_map('trim', explode('|', $line, 2));
        } else {
            $label = 'Asesor comercial '.($index + 1);
            $phone = $line;
        }

        $phoneDigits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($phoneDigits === '') {
            return null;
        }

        $message = $this->commercialWhatsappMessage($cliente, $chat);

        return [
            'label' => $label ?: 'Asesor comercial',
            'phone' => $phone,
            'whatsapp_label' => $phone,
            'whatsapp_url' => 'https://wa.me/'.$phoneDigits.'?text='.rawurlencode($message),
            'prefilled_message' => $message,
        ];
    }

    private function commercialWhatsappMessage($cliente = null, ?ClienteAiChat $chat = null): string
    {
        $name = trim((string) ($cliente?->name ?? ''));
        $company = trim((string) ($cliente?->company ?? ''));
        $identity = $name !== '' ? 'Soy '.$name : 'Soy cliente de AIQ';

        if ($company !== '') {
            $identity .= ' de '.$company;
        }

        $caseSummary = $this->commercialCaseSummary($chat);
        $message = 'Hola, '.$identity.'. Estuve conversando con el Asesor AIQ y quiero continuar mi consulta técnica.';

        if ($caseSummary !== '') {
            $message .= ' Resumen del caso: '.$caseSummary;
        }

        return $message;
    }

    private function commercialCaseSummary(?ClienteAiChat $chat): string
    {
        if (! $chat) {
            return '';
        }

        $messages = $chat->messages()
            ->where('role', 'user')
            ->latest()
            ->limit(30)
            ->pluck('content')
            ->reverse()
            ->map(fn ($content) => preg_replace('/\s+/', ' ', trim((string) $content)) ?? '')
            ->filter(function (string $content): bool {
                if (mb_strlen($content) < 18) {
                    return false;
                }

                $normalized = Str::lower(Str::ascii($content));

                return ! preg_match(
                    '/^(hola|buen dia|buenos dias|buenas|como estas|que tal)\b|'
                    .'(que datos (te di|proporcione)|tenes memoria|recordas|revisa (el |mas )?arriba|'
                    .'pasame (el )?whatsapp|mandame (el )?whatsapp|quiero hablar con|contactame)/i',
                    $normalized
                );
            })
            ->values();

        if ($messages->isEmpty()) {
            return '';
        }

        $selected = collect([$messages->first()])
            ->merge($messages->slice(-2))
            ->filter()
            ->unique()
            ->map(fn (string $content) => Str::limit($content, 220, '…'))
            ->values();

        return Str::limit($selected->implode(' | '), 560, '…');
    }

    private function normalizeGeminiModel(?string $model): string
    {
        $model = trim((string) ($model ?: 'gemini-3.5-flash'));
        $model = Str::after($model, 'models/');

        return rawurlencode($model);
    }

    private function resolveChat($cliente, string $type, ?int $chatId, string $input): ClienteAiChat
    {
        if ($chatId) {
            $chat = $cliente->aiChats()
                ->where('assistant_type', $type)
                ->whereNull('hidden_from_client_at')
                ->whereKey($chatId)
                ->first();

            if ($chat) {
                return $chat;
            }
        }

        return $cliente->aiChats()->create([
            'assistant_type' => $type,
            'title' => Str::limit(trim($input), 64),
            'last_message_at' => now(),
        ]);
    }

    private function chatHistory(ClienteAiChat $chat, int $limit = 20): array
    {
        return $chat->messages()
            ->whereIn('role', ['user', 'assistant'])
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->map(fn ($message) => [
                'role' => $message->role,
                'content' => $message->role === 'assistant'
                    ? $this->cleanAssistantMessage($message->content, $message->raw_response)
                    : trim($message->content.($message->attachment_path ? "\n[El cliente adjuntó una imagen para análisis visual.]" : '')),
                'created_at' => $message->created_at?->toISOString(),
                'has_image' => (bool) $message->attachment_path,
            ])
            ->values()
            ->all();
    }

    private function conversationHistoryText(array $history): string
    {
        return collect($history)
            ->map(function (array $message): string {
                $role = ($message['role'] ?? '') === 'assistant' ? 'ASESOR AIQ' : 'CLIENTE';
                $content = trim((string) ($message['content'] ?? ''));

                return $content !== '' ? $role.': '.$content : '';
            })
            ->filter()
            ->implode("\n\n");
    }

    private function diagnosticCaseContext(ClienteAiChat $chat): string
    {
        return $chat->messages()
            ->where('role', 'user')
            ->latest()
            ->limit(40)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($message, int $index) {
                $content = trim((string) $message->content);
                $imageNote = $message->attachment_path ? ' [incluye imagen]' : '';

                return ($index + 1).'. '.($content !== '' ? $content : 'Imagen enviada para análisis').$imageNote;
            })
            ->implode("\n");
    }

    private function diagnosticRetrievalQuery(ClienteAiChat $chat, string $currentInput): string
    {
        $recentUserContext = $chat->messages()
            ->where('role', 'user')
            ->latest()
            ->limit(8)
            ->pluck('content')
            ->reverse()
            ->filter()
            ->implode(' | ');

        return trim(implode(' | ', array_filter([
            'Diagnóstico técnico industrial AIQ',
            $recentUserContext,
            $currentInput,
        ])));
    }

    private function requestsRestrictedDocument(string $input): bool
    {
        $normalized = Str::lower(Str::ascii(trim($input)));

        if ($normalized === '') {
            return false;
        }

        $mentionsRestrictedDocument = (bool) preg_match(
            '/\b(ficha(?:\s+tecnica)?|hoja\s+de\s+seguridad|certificado|pdf|documento(?:\s+tecnico)?)\b/i',
            $normalized
        );
        $requestsDelivery = (bool) preg_match(
            '/\b(pasa(?:me|s)?|manda(?:me|s)?|envia(?:me|s)?|comparti(?:me|s)?|adjunta(?:me|s)?|'
            .'descarga(?:r)?|baja(?:r)?|dame|quiero|necesito|puedo\s+tener|tenes)\b/i',
            $normalized
        );

        return $mentionsRestrictedDocument && $requestsDelivery;
    }

    private function prepareMessagesForDisplay($messages)
    {
        return $messages->map(function ($message) {
            if ($message->role === 'assistant') {
                $message->content = $this->cleanAssistantMessage($message->content, $message->raw_response);
            }

            return $message;
        });
    }

    private function assistantMeta(string $type): array
    {
        abort_unless($type === self::DEFAULT_ASSISTANT_TYPE, 404);

        return [
            'tecnico' => [
                'title' => 'Asesor AIQ',
                'description' => 'Escribí tu consulta y recibí orientación de AIQ.',
                'label' => 'Consulta',
                'placeholder' => 'Escribí tu consulta sobre productos, procesos, aplicaciones o requisitos.',
                'config_key' => 'services.n8n.technical_webhook',
                'webhook_setting' => 'n8n_technical_webhook_url',
            ],
        ][$type];
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

    private function extractOutput(mixed $json, string $body): string
    {
        $output = $this->extractAssistantText($json);

        if (filled($output)) {
            return $output;
        }

        $output = $this->extractAssistantText($this->decodeJsonPayload($body));

        if (filled($output)) {
            return $output;
        }

        $body = trim($body);

        if (! filled($body) || $this->looksLikeJson($body)) {
            return self::EMPTY_ASSISTANT_MESSAGE;
        }

        return $body;
    }

    private function extractGeminiOutput(mixed $json, string $body): string
    {
        if (is_array($json)) {
            $parts = Arr::get($json, 'candidates.0.content.parts', []);

            if (is_array($parts)) {
                $text = collect($parts)
                    ->pluck('text')
                    ->filter(fn ($value) => is_string($value) && filled($value))
                    ->implode("\n\n");

                if (filled($text)) {
                    return $text;
                }
            }

            $errorMessage = Arr::get($json, 'error.message');
            if (is_string($errorMessage) && filled($errorMessage)) {
                return 'No se pudo generar la respuesta del asistente. Revisá la configuración del modelo en el panel de administración.';
            }
        }

        return filled($body) ? $body : self::EMPTY_ASSISTANT_MESSAGE;
    }

    private function cleanAssistantMessage(?string $content, mixed $rawResponse = null): string
    {
        $cleanContent = $this->extractOutput(null, (string) $content);

        if ($cleanContent !== self::EMPTY_ASSISTANT_MESSAGE) {
            return $cleanContent;
        }

        $cleanRawResponse = $this->extractOutput($rawResponse, '');

        return $cleanRawResponse !== self::EMPTY_ASSISTANT_MESSAGE
            ? $cleanRawResponse
            : $cleanContent;
    }

    private function extractAssistantText(mixed $payload): ?string
    {
        if (is_string($payload)) {
            $payload = trim($payload);

            if ($payload === '') {
                return null;
            }

            $decodedPayload = $this->decodeJsonPayload($payload);

            if ($decodedPayload !== null) {
                $decodedText = $this->extractAssistantText($decodedPayload);

                if (filled($decodedText)) {
                    return $decodedText;
                }
            }

            return $this->looksLikeJson($payload) ? null : $payload;
        }

        if (! is_array($payload)) {
            return null;
        }

        if (array_is_list($payload)) {
            foreach ($payload as $item) {
                $text = $this->extractAssistantText($item);

                if (filled($text)) {
                    return $text;
                }
            }

            return null;
        }

        foreach (self::ASSISTANT_TEXT_KEYS as $key) {
            $text = $this->extractAssistantText(Arr::get($payload, $key));

            if (filled($text)) {
                return $text;
            }
        }

        foreach (['data', 'body', 'result', 'json'] as $containerKey) {
            $text = $this->extractAssistantText(Arr::get($payload, $containerKey));

            if (filled($text)) {
                return $text;
            }
        }

        return null;
    }

    private function decodeJsonPayload(?string $payload): mixed
    {
        $payload = trim((string) $payload);

        if ($payload === '') {
            return null;
        }

        $payload = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $payload) ?? $payload;
        $decoded = json_decode($payload, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    private function looksLikeJson(string $payload): bool
    {
        $payload = trim($payload);

        if ($payload === '') {
            return false;
        }

        $first = $payload[0];
        $last = substr($payload, -1);

        return ($first === '{' && $last === '}')
            || ($first === '[' && $last === ']')
            || ($first === '"' && $last === '"');
    }

    private function layoutData(): array
    {
        return [
            'logo' => Logo::first(),
            'contacto' => Contacto::first(),
            'redes' => Rede::first(),
            'metadata' => null,
        ];
    }
}
