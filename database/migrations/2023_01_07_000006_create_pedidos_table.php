<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('id_proveedor');
            $table->unsignedBigInteger('id_usuario');
            $table->enum('estado', ['en proceso', 'enviado', 'completado'])->default('en proceso');
            $table->decimal('total', 8, 2);
            $table->timestamps();

            //Llave foránea con eliminación en cascada
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_proveedor')->references('id')->on('proveedores')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};
