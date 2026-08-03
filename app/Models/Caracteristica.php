<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Caracteristica extends Model
{
    use HasFactory;

    protected $fillable = ['orden', 'titulo', 'imagen'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }
}
