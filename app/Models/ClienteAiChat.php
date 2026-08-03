<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteAiChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'logincliente_id',
        'assistant_type',
        'title',
        'last_message_at',
        'hidden_from_client_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'hidden_from_client_at' => 'datetime',
    ];

    public function logincliente()
    {
        return $this->belongsTo(Logincliente::class, 'logincliente_id');
    }

    public function messages()
    {
        return $this->hasMany(ClienteAiChatMessage::class, 'cliente_ai_chat_id');
    }
}
