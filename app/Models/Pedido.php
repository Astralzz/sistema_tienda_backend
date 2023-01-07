<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    // Tiene muchos detalles de pedidos
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    //Pertenece al proveedor
    public function proveedor()
    {
        //Pertenece a
        return $this->belongsTo(Proveedor::class);
    }

    //Pertenece al usuario
    public function usuario()
    {
        //Pertenece a
        return $this->belongsTo(Usuario::class);
    }
}
