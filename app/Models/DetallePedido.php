<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    //Pertenece al pedido
    public function pedido()
    {
        //Pertenece a
        return $this->belongsTo(Pedido::class);
    }

    //Pertenece al producto
    public function producto()
    {
        //Pertenece a
        return $this->belongsTo(Producto::class);
    }
}
