<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha', 'id_usuario', 'id_proveedor', 'total', 'estado'
    ];


    // Tiene muchos detalles de pedidos
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido');
    }

    // //Pertenece al proveedor
    // public function proveedor()
    // {
    //     //Pertenece a
    //     return $this->belongsTo(Proveedor::class);
    // }

    // //Pertenece al usuario
    // public function usuario()
    // {
    //     //Pertenece a
    //     return $this->belongsTo(Usuario::class);
    // }
}
