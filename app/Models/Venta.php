<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha', 'id_usuario', 'total'
    ];

    // Tiene muchos detalles de pedidos
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }
}
