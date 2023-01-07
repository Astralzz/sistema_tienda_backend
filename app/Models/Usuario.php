<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    // Tiene muchos pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }


    // Tiene muchas ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
