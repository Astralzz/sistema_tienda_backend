<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProveedorController extends Controller
{


    //Lista completa
    public function lista($desde, $asta)
    {

        try {

            // Lista de usuarios
            $tabla = Proveedor::select(
                [
                    'id',
                    'nombre',
                    'email',
                    'telefono',
                    'direccion',
                    'empresa',
                ]
            )
                ->orderBy('nombre', 'asc') // orden alfabético
                ->skip($desde)->take($asta) // desde / asta
                ->get();

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
                'nombre' => 'required|string|min:2|max:120',
                'email' => 'required|string|min:5|max:95',
                'telefono' => 'required|string|min:10|max:13',
                'direccion' => 'nullable|string|min:2|max:160',
                'empresa' => 'nullable|string|min:2|max:120',
            ]);

            // Crear un nuevo proveedor
            $proveedor = new Proveedor();
            $proveedor->nombre = $request->nombre;
            $proveedor->email = $request->email;
            $proveedor->telefono = $request->telefono;
            $proveedor->direccion = $request->direccion;
            $proveedor->empresa = $request->empresa;

            // Guardamos
            $proveedor->save();

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
        try {

            // Validamos
            $validar = Validator::make(['nombre' => $nombre], [
                'nombre' => 'required|string|max:255'
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos usuario
            $tabla = DB::table('proveedores')
                ->select(
                    'id',
                    'nombre',
                    'email',
                    'telefono',
                    'direccion',
                    'empresa',
                )
                ->where('nombre', 'like', '%' . $nombre . '%')
                ->orderBy('nombre', 'asc')
                ->take($no)
                ->get();

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
            return Proveedor::count();
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
            $producto = Proveedor::where('id', $id)->first();

            // Encontrado
            if ($producto) {
                // eliminamos
                $producto->forceDelete();
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
