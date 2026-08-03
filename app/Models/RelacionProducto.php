<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelacionProducto extends Model
{
    protected $table = 'relacion_productos';

    protected $fillable = ['producto_id', 'categoria_id', 'subcategoria_id'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    
}
