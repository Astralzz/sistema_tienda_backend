<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Recursos\ClaseFunciones;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// Controlador de usuarios
class UsuarioController extends Controller
{
    //Lista completa
    public function lista($desde, $asta)
    {

        try {

            // Lista de usuarios
            $tabla = Usuario::select(
                [
                    'nombre',
                    'apellidos',
                    'email',
                    'telefono',
                    'isGerente',
                    'estado',
                    "imagen",
                    "isGerente"
                ]
            )
                ->orderBy('nombre', 'asc') // orden alfabético
                ->skip($desde)->take($asta) // desde / asta
                ->get();

            //Recorremos
            foreach ($tabla as $key => $usuario) {
                //Si existe
                if ($usuario->imagen !== null) {
                    if (Storage::exists("public/" . $usuario->imagen)) {
                        //Obtenemos imagen
                        $usuario->imagen = Storage::url($usuario->imagen);
                    }
                }
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
                'nombre' => 'required|string|min:2|max:60',
                'apellido_p' => 'required|string|min:2|max:40',
                'apellido_m' => 'required|string|min:2|max:40',
                'email' => 'required|string|min:5|max:35',
                'telefono' => 'required|string|min:10|max:13',
                'password' => 'required|string|min:5',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'isGerente' => 'required|in:true,false',
            ]);

            // Crear un nuevo usuario
            $usuario = new Usuario();
            $usuario->nombre = $request->nombre;
            $usuario->apellidos = $request->apellido_p . " " . $request->apellido_m;
            $usuario->email = $request->email;
            $usuario->telefono = $request->telefono;
            $usuario->password = Hash::make($request->password); //Encriptada
            $usuario->isGerente = ($request->isGerente == "true");

            // Se se envió una imagen
            if ($request->has('imagen')) {

                // Nombre para imagen sin espacios
                $nombreImagen = str_replace(" ", "", ($request->nombre . $request->apellido_p . $request->apellido_m));

                // Ruta
                $rutaGuardar = "img/usuarios";

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

    //Obtener
    public function obtener($email)
    {
        try {

            // Validamos
            $validar = Validator::make(['email' => $email], [
                'email' => 'required|string|max:255'
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos usuario
            $usuario = Usuario::select(
                'nombre',
                'apellidos',
                'email',
                'telefono',
                'isGerente',
                'imagen'
            )
                ->where('email', $email)->first();

            // Encontrado
            if ($usuario) {

                //Si existe
                if ($usuario->imagen !== null) {
                    if (Storage::exists("public/" . $usuario->imagen)) {
                        //Obtenemos imagen
                        $usuario->imagen = Storage::url($usuario->imagen);
                    } else {
                        $usuario->imagen = null;
                    }
                }

                //Devolvemos
                return response()->json($usuario);
            }

            // No encontrado
            return response()->json([]);

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
            $tabla = DB::table('usuarios')
                ->select(
                    'nombre',
                    'apellidos',
                    'email',
                    'telefono',
                    'estado',
                    'imagen',
                    "isGerente"
                )
                ->where('nombre', 'like', '%' . $nombre . '%')
                ->orWhere('apellidos', 'like', '%' . $nombre . '%')
                ->orderBy('nombre', 'asc')
                ->take($no)
                ->get();

            //Recorremos
            foreach ($tabla as $key => $usuario) {
                //Si existe
                if ($usuario->imagen !== null) {
                    if (Storage::exists("public/" . $usuario->imagen)) {
                        //Obtenemos imagen
                        $usuario->imagen = Storage::url($usuario->imagen);
                    }
                }
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

    //Buscar usuario
    public function buscar($email)
    {
        try {
            // Validamos
            $validar = Validator::make(['email' => $email], [
                'email' => 'required|string|max:255'
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos
            $usuario = Usuario::where('email', $email)->first();

            // Encontrado
            if ($usuario) {
                return response()->json(['estado' => true]);
            }

            // No encontrado
            return response()->json(['estado' => false]);


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

    //Validar usuario
    public function validar(Request $request)
    {
        try {
            // Validamos datos
            $request->validate([
                'email' => 'required|string|max:255',
                'password' => 'required|string|min:5',
            ]);

            // Buscamos usuario
            $usuario = Usuario::where('email', $request->email)->first();

            // Encontrado
            if ($usuario) {
                //Si esta activado
                if ($usuario->estado == 1) {
                    //Verificamos contraseña
                    if (Hash::check($request->password, $usuario->password)) {
                        // Éxito
                        return response()->json(['estado' => true]);
                    }
                }
            }

            // No encontrado
            return response()->json(['estado' => false]);

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

    //Activar/Desactivar usuario
    public function desactivar($email)
    {
        try {
            // Validamos
            $validar = Validator::make(['email' => $email], [
                'email' => 'required|string|max:255'
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos
            $usuario = Usuario::where('email', $email)->first();

            // Encontrado
            if ($usuario) {
                // Cambiamos
                $usuario->update(['estado' => !$usuario->estado]);
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

    //Modificar usuario
    public function modificar(Request $request)
    {

        // $data = $request->all();
        // $dd = "Lo que esta llegando es: \n";
        // foreach ($data as $key => $value) {
        //     $dd .= $key . ": " . $value . "\n";
        // }

        try {

            // Validamos datos
            $request->validate([
                'nombre' => 'required|string|min:2|max:60',
                'apellidos' => 'required|string|min:2|max:80',
                'email' => 'required|string|min:5|max:35',
                'oldEmail' => 'required|string|min:5|max:35',
                'telefono' => 'required|string|min:10|max:13',
                'password' => 'nullable|string|min:5',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            // Buscamos usuario
            $usuario = Usuario::where('email', $request->oldEmail)->first();

            // Encontrado
            if ($usuario) {
                // Guardamos
                $usuario->nombre = $request->nombre;
                $usuario->apellidos = $request->apellidos;
                $usuario->telefono = $request->telefono;
                $usuario->email = $request->email;

                // //Si se envió una password
                // if ($request->has('password')) {
                //     $usuario->password = Hash::make($request->password); //Encriptada
                // }

                // //Si se envió una imagen
                // if ($request->has('imagen')) {


                //     $rutaAntigua = null;
                //     //Si el usuario ya tenia una imagen
                //     if ($usuario->imagen !== null) {
                //         if (Storage::exists("public/" . $usuario->imagen)) {
                //             //Obtenemos ruta
                //             $rutaAntigua = $usuario->imagen;
                //         }
                //     }

                //     // Nombre para la nueva imagen
                //     $nombreImagen = str_replace(" ", "", ($request->nombre . $request->apellidos));

                //     // Ruta
                //     $rutaGuardar = "img/usuarios";

                //     //Guardamos imagen
                //     $file = $request->file("imagen");
                //     $ruta = ClaseFunciones::guardarArchivo($file, $rutaGuardar, $nombreImagen);

                //     //nueva ruta
                //     $usuario->imagen = $ruta;

                //     //Eliminamos
                //     if ($rutaAntigua !== null) {
                //         // eliminamos antigua imagen
                //         Storage::delete("public/" . $rutaAntigua);
                //     }
                // }

                //guardamos
                $usuario->save();
            }


            // No encontrado
            //throw new Exception();

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
            ], 503);
        }
    }

    // Validar key
    public function validarKey(Request $request)
    {

        try {

            // Validamos datos
            $request->validate([
                'key' => 'required|string|min:10',
            ]);

            // Existe
            if (env('ADMIN_KEY')) {

                $adminKey = env('ADMIN_KEY');

                //Correcta
                if ($adminKey == $request->key) {
                    return response()->json(['estado' => true]);
                }
            }

            //Incorrecta
            return response()->json(['estado' => false]);

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
    public function noDeFilas()
    {
        try {
            return Usuario::count();
        } catch (\Exception $e) {
            // Otro
            return response()->json([
                'error' => 'Error desconocido, error: ' . $e->getMessage()
            ], 501);
        }
    }
}
