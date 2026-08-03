<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['orden', 'titulo', 'slug', 'color', 'caracteristica_id', 'descripcion', 'imagen', 'galeria', 'pdf', 'destacado'];

    public function caracteristica()
    {
        return $this->belongsTo(Caracteristica::class);
    }

    protected $casts = [
        'galeria' => 'array',
    ];

    public function relaciones()
    {
        return $this->hasMany(RelacionProducto::class);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'relacion_productos', 'producto_id', 'categoria_id')
                    ->whereNotNull('relacion_productos.categoria_id');
    }

    public function subcategorias()
    {
        return $this->belongsToMany(Subcategoria::class, 'relacion_productos', 'producto_id', 'subcategoria_id')
                    ->whereNotNull('relacion_productos.subcategoria_id');
    }

    public function relacionados()
    {
        return $this->belongsToMany(Producto::class, 'prelaciones', 'producto_id', 'producto_relacionado_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orden', function (Builder $builder) {
            $builder->orderBy('orden');
        });
    }

}
