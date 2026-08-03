<?php

namespace Tests\Feature;

use App\Models\AiKnowledgeDocument;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function createApplication(): Application
    {
        $app = parent::createApplication();

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.n8n.api_key' => 'callback-secret']);
    }

    public function test_knowledge_callback_accepts_body_token_and_does_not_store_secrets(): void
    {
        $document = $this->createDocument();

        $response = $this
            ->withHeader('Authorization', 'Bearer null')
            ->postJson('/api/n8n/knowledge-callback', [
                'document_id' => $document->id,
                'status' => 'ready',
                'message' => 'PDF indexado correctamente',
                'callback_token' => 'callback-secret',
                'callback' => ['token' => 'callback-secret'],
                'api_key' => 'callback-secret',
                'gemini' => ['api_key' => 'gemini-secret'],
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'document_id' => $document->id,
                'status' => 'ready',
            ]);

        $document->refresh();

        $this->assertSame('ready', $document->status);
        $this->assertNotNull($document->processed_at);
        $this->assertSame([
            'document_id' => $document->id,
            'status' => 'ready',
            'message' => 'PDF indexado correctamente',
        ], $document->raw_response);
    }

    public function test_knowledge_callback_rejects_bearer_null(): void
    {
        $document = $this->createDocument();

        $response = $this
            ->withHeader('Authorization', 'Bearer null')
            ->postJson('/api/n8n/knowledge-callback', [
                'document_id' => $document->id,
                'status' => 'ready',
                'message' => 'PDF indexado correctamente',
            ]);

        $response->assertForbidden();

        $this->assertSame('uploaded', $document->refresh()->status);
    }

    private function createDocument(): AiKnowledgeDocument
    {
        return AiKnowledgeDocument::query()->create([
            'assistant_type' => 'tecnico',
            'title' => 'Manual AIQ',
            'original_name' => 'manual.pdf',
            'file_path' => 'knowledge/manual.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1234,
            'status' => 'uploaded',
        ]);
    }
}
