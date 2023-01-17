<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Usuario;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    //Lista completa
    public function lista($desde, $asta)
    {

        try {

            // Lista de usuarios
            $tabla = Pedido::select(
                [
                    'id',
                    'fecha',
                    'id_proveedor',
                    'id_usuario',
                    'estado',
                    'total',
                ]
            )
                ->orderBy('fecha', 'asc') // orden alfabético
                ->skip($desde)->take($asta) // desde / asta
                ->with(['detalles'])
                ->get();

            //Recorremos
            foreach ($tabla as $value) {
                $value->detalle_no = count($value->detalles);
                $value->proveedor = Proveedor::select('nombre')->find($value->id_proveedor);
                $value->usuario = Usuario::select('nombre', 'apellidos')->find($value->id_usuario);
                $value->detalles->map(function ($detalle) {
                    $detalle->producto = Producto::select('nombre')->find($detalle->id_producto);
                });
            }

            //Retornamos
            return $tabla;

            //Errores
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
    public function getListaPorNombre($fecha, $no)
    {
        try {

            // Validamos
            $validar = Validator::make(['fecha' => $fecha], [
                'fecha' => 'required|date'
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos usuario
            $tabla = Pedido::select(
                'id',
                'fecha',
                'id_proveedor',
                'id_usuario',
                'estado',
                'total',
            )
                ->whereDate('fecha', '=', $fecha)
                ->orderBy('fecha', 'asc')
                ->take($no)
                ->with(['detalles'])
                ->get();

            //Recorremos
            foreach ($tabla as $value) {
                $value->detalle_no = count($value->detalles);
                $value->proveedor = Proveedor::select('nombre')->find($value->id_proveedor);
                $value->usuario = Usuario::select('nombre', 'apellidos')->find($value->id_usuario);
                $value->detalles->map(function ($detalle) {
                    $detalle->producto = Producto::select('nombre')->find($detalle->id_producto);
                });
            }
            //Retornamos
            return $tabla;


            //Errores
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

    // Obtener el numero de filas
    public function getFilas()
    {
        try {
            return Pedido::count();
        } catch (\Exception $e) {
            // Otro
            return response()->json([
                'error' => 'Error desconocido, error: ' . $e->getMessage()
            ], 501);
        }
    }

    //Eliminar
    public function eliminar($id)
    {
        try {
            // Validamos
            $validar = Validator::make(['id' => $id], [
                'id' => 'required|numeric',
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos
            $pedido = Pedido::where('id', $id)->first();

            // Encontrado
            if ($pedido) {
                // eliminamos
                $pedido->forceDelete();
                return;
            }

            // No encontrado
            throw new Exception();

            //Errores
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

    //Eliminar
    public function completar($id, $id_producto, $cantidad)
    {
        try {
            // Validamos
            $validar = Validator::make(
                [
                    'id' => $id,
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad
                ],
                [
                    'id' => 'required|numeric',
                    'id_producto' => 'required|numeric',
                    'cantidad' => 'required|numeric|min:0',
                ]
            );

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos
            $pedido = Pedido::where('id', $id)->first();
            // Buscamos
            $producto = Producto::where('id', $id_producto)->first();

            // Encontrado
            if ($pedido && $producto) {
                // completamos
                $pedido->estado = "completado";
                $pedido->save();
                // aumentamos
                $producto->cantidad =  $producto->cantidad + $cantidad;
                $producto->save();
                return;
            }

            // No encontrado
            throw new Exception();

            //Errores
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
}
