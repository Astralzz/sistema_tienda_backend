<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
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
    Route::get('/tienda/usuarios/buscar/no/filas', 'getFilas')->name("getNoDeFilasUsuario");
});

//Categorias
Route::controller(CategoriaController::class)->group(function () {
    // //Lista de usuario
    // Route::get('tienda/categorias/lista/{desde}/{asta}', 'lista')->name("listaDeUsuarios");

    //Lista de nombres categorias
    Route::get('tienda/categorias/lista/nombres', 'listaNombres')->name("listaDeNombresCategorias");

    // //Obtener usuario
    // Route::get('tienda/categorias/obtener/{email}', 'obtener')->name("registrarUsuario");
    //Guardar usuario
    Route::post('tienda/categorias/guardar', 'guardar')->name("registrarCategoria");
    // //Buscar usuario
    // Route::get('tienda/categorias/buscar/{email}', 'buscar')->name("buscarUsuarioPorEmail");
    // //Versificar usuario y credenciales
    // Route::post('tienda/categorias/validar', 'validar')->name("verificarUsuario");
    // //Desactivar usuario
    // Route::put('tienda/categorias/desactivar/{email}', 'desactivar')->name("desactivarUsuario");
    // //Modificar usuario
    // Route::put('tienda/categorias/modificar', 'modificar')->name("modificarUsuario");
    // //Validar key
    // Route::get('tienda/categorias/validar/key', 'validarKey')->name("validarKeyUsuario");
    // //Buscar usuario por nombre
    // Route::get('tienda/categorias/buscar/nombre/{nombre}/{no}', 'getListaPorNombre')->name("getListaPorNombreUsuarios");
    // // Obtener numero de filas
    // Route::get('tienda/categorias/buscar/no/filas', 'getFilas')->name("getNoDeFilasUsuario");
});


//Productos
Route::controller(ProductoController::class)->group(function () {
    //Lista de productos
    Route::get('/tienda/productos/lista/{desde}/{asta}', 'lista')->name("listaDeProductos");
    //Lista de productos
    Route::get('/tienda/productos/buscar/lista/nombre/{nombre}/{no}', 'listaPorNombre')->name("listaDeProductos");
    //Guardar producto-
    Route::post('/tienda/productos/guardar', 'guardar')->name("registrarProducto");
    // //Buscar usuario
    // Route::get('/tienda/productos/buscar/{email}', 'buscar')->name("buscarUsuarioPorEmail");
    // //Versificar usuario y credenciales
    // Route::post('/tienda/productos/validar', 'validar')->name("verificarUsuario");
    //Eliminar usuario
    Route::delete('/tienda/productos/eliminar/{id}', 'eliminar')->name("eliminarProducto");
    // //Modificar usuario
    // Route::put('/tienda/productos/modificar', 'modificar')->name("modificarUsuario");
    // //Validar key
    // Route::get('/tienda/productos/validar/key', 'validarKey')->name("validarKeyUsuario");
    //Buscar usuario por nombre
    Route::get('/tienda/productos/buscar/nombre/{nombre}/{no}', 'getListaPorNombre')->name("getListaPorNombreProducto");
    // Obtener numero de filas
    Route::get('/tienda/productos/buscar/no/filas', 'getFilas')->name("getNoDeFilasProducto");
});
