<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Categoria extends Model
{

    use HasFactory;

    protected $fillable = ['orden', 'titulo', 'slug', 'imagen', 'destacado'];

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }


      protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }
}
