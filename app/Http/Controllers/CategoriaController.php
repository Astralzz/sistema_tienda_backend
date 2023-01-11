<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Recursos\ClaseFunciones;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CategoriaController extends Controller
{


    //Lista completa
    public function listaNombres()
    {

        try {

            // Lista de usuarios
            $tabla = Categoria::select(
                [
                    'id',
                    'nombre',
                ]
            )
                ->orderBy('nombre', 'asc') // orden alfabético
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
                'nombre' => 'required|string|min:2|max:190',
                'descripcion' => 'nullable|string|min:2|max:2000',
            ]);

            // Crear un nueva categoria
            $categoria = new Categoria();
            $categoria->nombre = $request->nombre;
            $categoria->descripcion = $request->descripcion;

            // Guardamos
            $categoria->save();

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
}
