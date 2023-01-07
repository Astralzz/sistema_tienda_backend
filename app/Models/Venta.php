<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // Tiene muchos detalles de venta
    public function details()
    {
        return $this->hasMany(DetalleVenta::class);
    }


    //Pertenece al usuario
    public function usuario()
    {
        //Pertenece a
        return $this->belongsTo(Usuario::class);
    }
}
