<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Novedades extends Model
{
    use HasFactory;

    protected $fillable = ['orden', 'titulo', 'descripcion', 'imagen', 'descripcion_corto', 'galeria'];

    protected $casts = [
        'galeria' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }

}
