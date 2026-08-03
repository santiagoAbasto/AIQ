<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = ['categoria_id', 'orden', 'titulo', 'slug', 'imagen', 'destacado'];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function relaciones()
    {
        return $this->hasMany(RelacionProducto::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'relacion_productos', 'subcategoria_id', 'producto_id')
                    ->whereNotNull('relacion_productos.subcategoria_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }
}
