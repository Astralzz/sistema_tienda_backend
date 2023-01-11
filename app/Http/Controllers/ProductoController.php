<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Recursos\ClaseFunciones;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{

    //Lista completa
    public function lista($desde, $asta)
    {

        try {

            // Lista de usuarios
            $tabla = Producto::select(
                [
                    'id',
                    'nombre',
                    'id_categoria',
                    'precio',
                    'cantidad',
                    "imagen",
                    "descripcion"
                ]
            )
                ->orderBy('nombre', 'asc') // orden alfabético
                ->skip($desde)->take($asta) // desde / asta
                ->get();

            //Recorremos
            foreach ($tabla as $key => $producto) {
                //Si existe
                if ($producto->imagen !== null) {
                    //Obtenemos ruta
                    if (Storage::exists("public/" . $producto->imagen)) {
                        //Obtenemos imagen
                        $producto->imagen = Storage::url($producto->imagen);
                    }
                }

                //Ponemos categoria
                $categoria = Categoria::find($producto->id_categoria);
                $producto->categoria = $categoria->nombre;
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

    //Obtener lista por nombre para venta
    public function listaPorNombre($nombre, $no)
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
            $tabla = DB::table('productos')
                ->select(
                    'id',
                    'nombre',
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
            $tabla = DB::table('productos')
                ->select(
                    'id',
                    'nombre',
                    'id_categoria',
                    'precio',
                    'cantidad',
                    "imagen",
                    "descripcion"
                )
                ->where('nombre', 'like', '%' . $nombre . '%')
                ->orWhere('descripcion', 'like', '%' . $nombre . '%')
                ->orderBy('nombre', 'asc')
                ->take($no)
                ->get();

            //Recorremos
            foreach ($tabla as $key => $producto) {
                //Si existe
                if ($producto->imagen !== null) {
                    if (Storage::exists("public/" . $producto->imagen)) {
                        //Obtenemos imagen
                        $producto->imagen = Storage::url($producto->imagen);
                    }
                }

                //Ponemos categoria
                $categoria = Categoria::find($producto->id_categoria);
                $producto->categoria = $categoria->nombre;
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

    //Registrar
    public function guardar(Request $request)
    {

        try {

            // Validamos datos
            $request->validate([
                'nombre' => 'required|string|min:2|max:150',
                'categoria' => 'required|numeric',
                'precio' => 'required|numeric|min:0',
                'cantidad' => 'required|numeric|min:0',
                'descripcion' => 'nullable|string|min:2|max:1200',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,jfif|max:2048',
            ]);

            // Crear un nuevo usuario
            $usuario = new Producto();
            $usuario->nombre = $request->nombre;
            $usuario->id_categoria = $request->categoria;
            $usuario->precio = $request->precio;
            $usuario->cantidad = $request->cantidad;
            $usuario->descripcion = $request->descripcion;


            // Se se envió una imagen
            if ($request->has('imagen')) {

                // Nombre para imagen sin espacios
                $nombreImagen = str_replace(" ", "", ($request->nombre));
                $nombreImagen = str_replace(".", "", ($nombreImagen));

                // Ruta
                $rutaGuardar = "img/productos";

                //Guardamos imagen
                $file = $request->file("imagen");
                $ruta = ClaseFunciones::guardarArchivo($file, $rutaGuardar, $nombreImagen);

                //Guardamos en la bd
                $usuario->imagen = $ruta;
            }

            // Guardamos
            $usuario->save();

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

    // Obtener el numero de filas
    public function getFilas()
    {
        try {
            return Producto::count();
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
            $producto = Producto::where('id', $id)->first();

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
