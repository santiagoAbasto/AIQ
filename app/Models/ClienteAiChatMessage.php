<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteAiChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_ai_chat_id',
        'cliente_ai_request_id',
        'role',
        'content',
        'attachment_path',
        'attachment_mime',
        'attachment_name',
        'status',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function chat()
    {
        return $this->belongsTo(ClienteAiChat::class, 'cliente_ai_chat_id');
    }

    public function request()
    {
        return $this->belongsTo(ClienteAiRequest::class, 'cliente_ai_request_id');
    }
}
