<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    //Lista completa
    public function lista($desde, $asta)
    {

        // try {

        //     // Lista de usuarios
        //     $tabla = Pedido::select(
        //         [
        //             'id',
        //             'nombre',
        //             'email',
        //             'telefono',
        //             'direccion',
        //             'empresa',
        //         ]
        //     )
        //         ->orderBy('nombre', 'asc') // orden alfabético
        //         ->skip($desde)->take($asta) // desde / asta
        //         ->get();

        //     //Retornamos
        //     return $tabla;

        //     //Errores
        // } catch (QueryException $e) {
        //     // Consulta
        //     return response()->json([
        //         'error' => 'Error en la consulta, error: ' . $e->getMessage()
        //     ], 500);
        // } catch (\Exception $e) {
        //     // Otro
        //     return response()->json([
        //         'error' => 'Error desconocido, error: ' . $e->getMessage()
        //     ], 501);
        // }
    }

    //Registrar
    public function guardar(Request $request)
    {

        try {

            // Validamos datos
            $request->validate([
                'id_usuario' => 'required|numeric|min:0',
                'id_proveedor' => 'required|numeric|min:0',
                'id_producto' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'cantidad' => 'required|numeric|min:0',
                'fecha' => 'required|date',
                'descripcion' => 'nullable|string|min:2|max:1200',
            ]);

            // Crear un nuevo pedido
            $pedido = new Pedido();
            $pedido->fecha = $request->fecha;
            $pedido->id_usuario = $request->id_usuario;
            $pedido->id_proveedor = $request->id_proveedor;
            $pedido->total = $request->total;

            // Guardamos
            $pedido->save();

            $detalles = [
                [
                    'id_producto' => $request->id_producto,
                    'descripcion' => $request->descripcion,
                    'cantidad' => $request->cantidad,
                ],
            ];
            $pedido->detalles()->createMany($detalles);

            //Preparamos detalle (se puede mas de 1 arreglo)
            // $detalles = [
            //     [
            //         'id_producto' => $request->id_producto,
            //         'descripcion' => $request->descripcion,
            //         'cantidad' => $request->cantidad,
            //     ],
            // ];

            // //Creamos detalles de pedidos
            // $pedido->detalles()->saveMany(new DetallePedido($detalles));

            // Errores
        } catch (ValidationException $e) {
            // Validación
            return response()->json([
                'error' =>  'Error en la validación, error: ' . $e->getMessage()
            ], 400);
        } catch (QueryException $e) {
            // Consulta
            return response()->json([
                'error' => 'Error en la consulta, error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Otro
            return response()->json([
                'error' => 'Error desconocido, error: ' . $e->getMessage()
            ], 501);
        }
    }

    //Obtener por nombre
    public function getListaPorNombre($nombre, $no)
    {
        // try {

        //     // Validamos
        //     $validar = Validator::make(['nombre' => $nombre], [
        //         'nombre' => 'required|string|max:255'
        //     ]);

        //     //Error al validar
        //     if ($validar->fails()) {
        //         throw new ValidationException();
        //     }

        //     // Buscamos usuario
        //     $tabla = DB::table('pedidoes')
        //         ->select(
        //             'id',
        //             'nombre',
        //             'email',
        //             'telefono',
        //             'direccion',
        //             'empresa',
        //         )
        //         ->where('nombre', 'like', '%' . $nombre . '%')
        //         ->orderBy('nombre', 'asc')
        //         ->take($no)
        //         ->get();

        //     //Retornamos
        //     return $tabla;


        //     //Errores
        // } catch (ValidationException $e) {
        //     // Validación
        //     return response()->json([
        //         'error' =>  'Error en la validación, error: ' . $e->getMessage()
        //     ], 400);
        // } catch (QueryException $e) {
        //     // Consulta
        //     return response()->json([
        //         'error' => 'Error en la consulta, error: ' . $e->getMessage()
        //     ], 500);
        // } catch (\Exception $e) {
        //     // Otro
        //     return response()->json([
        //         'error' => 'Error desconocido, error: ' . $e->getMessage()
        //     ], 501);
        // }
    }

    // Obtener el numero de filas
    public function getFilas()
    {
        // try {
        //     return Pedido::count();
        // } catch (\Exception $e) {
        //     // Otro
        //     return response()->json([
        //         'error' => 'Error desconocido, error: ' . $e->getMessage()
        //     ], 501);
        // }
    }

    //Eliminar
    public function eliminar($id)
    {
        // try {
        //     // Validamos
        //     $validar = Validator::make(['id' => $id], [
        //         'id' => 'required|numeric',
        //     ]);

        //     //Error al validar
        //     if ($validar->fails()) {
        //         throw new ValidationException();
        //     }

        //     // Buscamos
        //     $producto = Pedido::where('id', $id)->first();

        //     // Encontrado
        //     if ($producto) {
        //         // eliminamos
        //         $producto->forceDelete();
        //         return;
        //     }

        //     // No encontrado
        //     throw new Exception();

        //     //Errores
        // } catch (ValidationException $e) {
        //     // Validación
        //     return response()->json([
        //         'error' =>  'Error en la validación, error: ' . $e->getMessage()
        //     ], 400);
        // } catch (QueryException $e) {
        //     // Consulta
        //     return response()->json([
        //         'error' => 'Error en la consulta, error: ' . $e->getMessage()
        //     ], 500);
        // } catch (\Exception $e) {
        //     // Otro
        //     return response()->json([
        //         'error' => 'Error desconocido, error: ' . $e->getMessage()
        //     ], 501);
        // }
    }
}
