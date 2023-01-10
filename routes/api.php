<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Usuarios
Route::controller(UsuarioController::class)->group(function () {
    //Lista de usuario
    Route::get('/tienda/usuarios/lista/{desde}/{asta}', 'lista')->name("listaDeUsuarios");
    //Obtener usuario
    Route::get('/tienda/usuarios/obtener/{email}', 'obtener')->name("registrarUsuario");
    //Guardar usuario
    Route::post('/tienda/usuarios/guardar', 'guardar')->name("registrarUsuario");
    //Buscar usuario
    Route::get('/tienda/usuarios/buscar/{email}', 'buscar')->name("buscarUsuarioPorEmail");
    //Versificar usuario y credenciales
    Route::post('/tienda/usuarios/validar', 'validar')->name("verificarUsuario");
    //Desactivar usuario
    Route::put('/tienda/usuarios/desactivar/{email}', 'desactivar')->name("desactivarUsuario");
    //Modificar usuario
    Route::put('/tienda/usuarios/modificar', 'modificar')->name("modificarUsuario");
    //Validar key
    Route::get('/tienda/usuarios/validar/key', 'validarKey')->name("validarKeyUsuario");
    //Buscar usuario por nombre
    Route::get('/tienda/usuarios/buscar/nombre/{nombre}/{no}', 'getListaPorNombre')->name("getListaPorNombreUsuarios");
    // Obtener numero de filas
    Route::get('/tienda/usuarios/buscar/filas', 'noDeFilas')->name("getNoDeFilasUsuario");
});
