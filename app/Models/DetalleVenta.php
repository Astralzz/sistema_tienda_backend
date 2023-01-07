<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    //Pertenece al la venta
    public function venta()
    {
        //Pertenece a
        return $this->belongsTo(Venta::class);
    }

    //Pertenece al producto
    public function producto()
    {
        //Pertenece a
        return $this->belongsTo(Producto::class);
    }
}
