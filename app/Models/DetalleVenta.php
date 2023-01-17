<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cantidad', 'id_producto', 'descripcion'
    ];

    //Pertenece al la venta
    public function venta()
    {
        //Pertenece a
        return $this->belongsTo(Venta::class);
    }
}
