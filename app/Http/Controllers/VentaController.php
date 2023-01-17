<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    //Lista completa
    public function lista($desde, $asta)
    {

        try {

            // Buscamos usuario
            $tabla = Venta::select(
                'id',
                'fecha',
                'id_usuario',
                'total',
            )
                ->orderBy('fecha', 'asc')
                ->skip($desde)->take($asta) // desde / asta
                ->with(['detalles'])
                ->get();

            //Recorremos
            foreach ($tabla as $value) {
                $value->filas_tabla = count($tabla);
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
                'id_producto' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'cantidad' => 'required|numeric|min:0',
                'fecha' => 'required|date',
                'descripcion' => 'nullable|string|min:2|max:1200',
            ]);

            // Crear un nuevo venta
            $venta = new Venta();
            $venta->fecha = $request->fecha;
            $venta->id_usuario = $request->id_usuario;
            $venta->total = $request->total;

            // Guardamos
            $venta->save();

            $detalles = [
                [
                    'id_producto' => $request->id_producto,
                    'descripcion' => $request->descripcion,
                    'cantidad' => $request->cantidad,
                ],
            ];
            $venta->detalles()->createMany($detalles);


            //Disminuimos venta
            VentaController::disminuirProductos($request->id_producto, $request->cantidad);

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
    public function getListaPorId($id, $desde, $asta)
    {
        try {

            // Validamos
            $validar = Validator::make(['id' => $id], [
                'id' => 'required|numeric|min:0',
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos usuario
            $tabla = Venta::select(
                'id',
                'fecha',
                'id_usuario',
                'total',
            )
                ->where('id_usuario', '=', $id)
                ->orderBy('fecha', 'asc')
                ->skip($desde)->take($asta) // desde / asta
                ->with(['detalles'])
                ->get();

            //Recorremos
            foreach ($tabla as $value) {
                $value->filas_tabla = count($tabla);
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

    //Obtener por nombre
    public function getListaPorFecha($fecha, $no)
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
            $tabla = Venta::select(
                'id',
                'fecha',
                'id_usuario',
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
                $value->usuario = Usuario::select('nombre', 'apellidos')->find($value->id_usuario);
                $value->detalles->map(function ($detalle) {
                    $detalle->producto = Producto::select('nombre')->find($detalle->id_producto);
                });
            }
            //Retornamos
            return $tabla;


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
            return Venta::count();
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
            $venta = Venta::where('id', $id)->first();

            // Encontrado
            if ($venta) {
                // eliminamos
                $venta->forceDelete();
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

    //Disminuir
    private function disminuirProductos($id_producto, $cantidad)
    {
        try {

            // Buscamos
            $producto = Producto::where('id', $id_producto)->first();

            // Encontrado
            if ($producto) {
                // disminuimos
                $producto->cantidad =  $producto->cantidad - $cantidad;
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
