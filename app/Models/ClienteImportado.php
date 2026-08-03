<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteImportado extends Model
{
    use HasFactory;

    protected $table = 'cliente_importados';

    protected $fillable = [
        'logincliente_id',
        'nombre',
        'email',
        'empresa',
        'telefono',
        'producto',
        'consulta',
        'raw_data',
        'source_file',
        'imported_at',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'imported_at' => 'datetime',
    ];

    public function logincliente()
    {
        return $this->belongsTo(Logincliente::class, 'logincliente_id');
    }
}
