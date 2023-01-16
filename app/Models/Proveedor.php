<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{

    protected $table = 'proveedores';
    use HasFactory;

    // Tiene muchos pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    // Valores editables
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion',
        'empresa',
    ];
}
