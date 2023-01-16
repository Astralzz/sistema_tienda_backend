<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cantidad', 'id_producto', 'descripcion'
    ];

    //Pertenece al pedido
    public function pedido()
    {
        //Pertenece a
        return $this->belongsTo(Pedido::class);
        // return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    // //Pertenece al producto
    // public function producto()
    // {
    //     //Pertenece a
    //     return $this->belongsTo(Producto::class);
    // }
}
