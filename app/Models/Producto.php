<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Pertenece a la categoría
    public function category()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Tiene muchas ventas
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // Tiene muchos pedidos
    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
