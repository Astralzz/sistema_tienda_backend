<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
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



//Proveedores
Route::controller(ProveedorController::class)->group(function () {
    //Lista de proveedores
    Route::get('/tienda/proveedores/lista/{desde}/{asta}', 'lista')->name("listaDeProveedores");
    // //Obtener usuario
    // Route::get('/tienda/proveedores/obtener/{email}', 'obtener')->name("registrarProveedor");
    //Guardar proveedor
    Route::post('/tienda/proveedores/guardar', 'guardar')->name("registrarProveedor");
    //Eliminar proveedor
    Route::delete('/tienda/proveedores/eliminar/{id}', 'eliminar')->name("eliminarProducto");
    // //Buscar usuario
    // Route::get('/tienda/proveedores/buscar/{email}', 'buscar')->name("buscarProveedorPorEmail");
    // //Versificar usuario y credenciales
    // Route::post('/tienda/proveedores/validar', 'validar')->name("verificarProveedor");
    // //Desactivar usuario
    // Route::put('/tienda/proveedores/desactivar/{email}', 'desactivar')->name("desactivarProveedor");
    // //Modificar usuario
    // Route::put('/tienda/proveedores/modificar', 'modificar')->name("modificarProveedor");
    // //Validar key
    // Route::get('/tienda/proveedores/validar/key', 'validarKey')->name("validarKeyProveedor");
    //Buscar proveedor por nombre
    Route::get('/tienda/proveedores/buscar/nombre/{nombre}/{no}', 'getListaPorNombre')->name("getListaPorNombreProveedores");
    // Obtener numero de filas
    Route::get('/tienda/proveedores/buscar/no/filas', 'getFilas')->name("getNoDeFilasProveedor");
});


//Pedidos
Route::controller(PedidoController::class)->group(function () {
    // //Lista de pedido
    // Route::get('/tienda/pedidos/lista/{desde}/{asta}', 'lista')->name("listaDePedidos");
    // //Obtener pedido
    // Route::get('/tienda/pedidos/obtener/{email}', 'obtener')->name("registrarPedido");
    //Guardar pedido
    Route::post('/tienda/pedidos/guardar', 'guardar')->name("registrarPedido");
    // //Buscar pedido
    // Route::get('/tienda/pedidos/buscar/{email}', 'buscar')->name("buscarPedidoPorEmail");
    // //Versificar pedido y credenciales
    // Route::post('/tienda/pedidos/validar', 'validar')->name("verificarPedido");
    // //Desactivar pedido
    // Route::put('/tienda/pedidos/desactivar/{email}', 'desactivar')->name("desactivarPedido");
    // //Modificar pedido
    // Route::put('/tienda/pedidos/modificar', 'modificar')->name("modificarPedido");
    // //Validar key
    // Route::get('/tienda/pedidos/validar/key', 'validarKey')->name("validarKeyPedido");
    // //Buscar pedido por nombre
    // Route::get('/tienda/pedidos/buscar/nombre/{nombre}/{no}', 'getListaPorNombre')->name("getListaPorNombrePedidos");
    // // Obtener numero de filas
    // Route::get('/tienda/pedidos/buscar/no/filas', 'getFilas')->name("getNoDeFilasPedido");
});
