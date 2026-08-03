<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiKnowledgeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistant_type',
        'title',
        'original_name',
        'file_path',
        'mime_type',
        'size',
        'status',
        'notes',
        'n8n_document_id',
        'raw_response',
        'processed_at',
        'uploaded_by',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'processed_at' => 'datetime',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getPublicUrlAttribute(): string
    {
        return asset('storage/'.ltrim($this->file_path, '/'));
    }

    public function getAssistantLabelAttribute(): string
    {
        return match ($this->assistant_type) {
            'tecnico', 'comercial' => 'Asesor AIQ',
            default => ucfirst((string) $this->assistant_type),
        };
    }
}
