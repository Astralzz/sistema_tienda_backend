<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    // Muchos productos
    public function products()
    {
        // Tiene muchos
        return $this->hasMany(Producto::class);
    }
}
