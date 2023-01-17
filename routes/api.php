<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
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
    //Lista de nombres categorias
    Route::get('tienda/categorias/lista/nombres', 'listaNombres')->name("listaDeNombresCategorias");
    //Guardar usuario
    Route::post('tienda/categorias/guardar', 'guardar')->name("registrarCategoria");
});


//Productos
Route::controller(ProductoController::class)->group(function () {
    //Lista de productos
    Route::get('/tienda/productos/lista/{desde}/{asta}', 'lista')->name("listaDeProductos");
    //Lista de productos
    Route::get('/tienda/productos/buscar/lista/nombre/{nombre}/{no}', 'listaPorNombre')->name("listaDeProductos");
    //Guardar producto-
    Route::post('/tienda/productos/guardar', 'guardar')->name("registrarProducto");
    //Eliminar usuario
    Route::delete('/tienda/productos/eliminar/{id}', 'eliminar')->name("eliminarProducto");
    //Buscar usuario por nombre
    Route::get('/tienda/productos/buscar/nombre/{nombre}/{no}', 'getListaPorNombre')->name("getListaPorNombreProducto");
    // Obtener numero de filas
    Route::get('/tienda/productos/buscar/no/filas', 'getFilas')->name("getNoDeFilasProducto");
});



//Proveedores
Route::controller(ProveedorController::class)->group(function () {
    //Lista de proveedores
    Route::get('/tienda/proveedores/lista/{desde}/{asta}', 'lista')->name("listaDeProveedores");
    //Guardar proveedor
    Route::post('/tienda/proveedores/guardar', 'guardar')->name("registrarProveedor");
    //Eliminar proveedor
    Route::delete('/tienda/proveedores/eliminar/{id}', 'eliminar')->name("eliminarProducto");
    //Buscar proveedor por nombre
    Route::get('/tienda/proveedores/buscar/nombre/{nombre}/{no}', 'getListaPorNombre')->name("getListaPorNombreProveedores");
    // Obtener numero de filas
    Route::get('/tienda/proveedores/buscar/no/filas', 'getFilas')->name("getNoDeFilasProveedor");
});


//Pedidos
Route::controller(PedidoController::class)->group(function () {
    //Lista de pedido
    Route::get('/tienda/pedidos/lista/{desde}/{asta}', 'lista')->name("listaDePedidos");
    //Guardar pedido
    Route::post('/tienda/pedidos/guardar', 'guardar')->name("registrarPedido");
    //Buscar pedido por fecha
    Route::get('/tienda/pedidos/buscar/fecha/{fecha}/{no}', 'getListaPorNombre')->name("getListaPorNombrePedidos");
    // Obtener numero de filas
    Route::get('/tienda/pedidos/buscar/no/filas', 'getFilas')->name("getNoDeFilasPedido");
    //Eliminar pedido
    Route::delete('/tienda/pedidos/eliminar/{id}', 'eliminar')->name("eliminarPedido");
    //Completar pedido
    Route::post('/tienda/pedidos/completar/{id}/{id_producto}/{cantidad}', 'completar')->name("completarPedido");
});

//Ventas
Route::controller(VentaController::class)->group(function () {
    //Lista de venta
    Route::get('/tienda/ventas/lista/{desde}/{asta}', 'lista')->name("listaDeVentas");
    //Lista de mis ventas
    Route::get('/tienda/ventas/mis_ventas/{id}/{desde}/{asta}', 'getListaPorId')->name("listaDeMisVentas");
    //Guardar venta
    Route::post('/tienda/ventas/guardar', 'guardar')->name("registrarVenta");
    //Buscar venta por fecha
    Route::get('/tienda/ventas/buscar/fecha/{fecha}/{no}', 'getListaPorFecha')->name("getListaPorNombreVentas");
    // Obtener numero de filas
    Route::get('/tienda/ventas/buscar/no/filas', 'getFilas')->name("getNoDeFilasVenta");
    //Eliminar venta
    Route::delete('/tienda/ventas/eliminar/{id}', 'eliminar')->name("eliminarVenta");
});
