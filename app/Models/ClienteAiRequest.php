<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteAiRequest extends Model
{
    use HasFactory;

    protected $table = 'cliente_ai_requests';

    protected $fillable = [
        'logincliente_id',
        'assistant_type',
        'input',
        'output',
        'status',
        'webhook_url',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function logincliente()
    {
        return $this->belongsTo(Logincliente::class, 'logincliente_id');
    }
}
