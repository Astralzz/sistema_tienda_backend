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

// Controlador de usuarios
class UsuarioController extends Controller
{
    //Lista completa
    public function lista($desde, $asta)
    {
        // Lista de usuarios
        return Usuario::select(
            [
                'nombre',
                'apellidos',
                'email',
                'telefono',
                'isGerente',
                'estado'
            ]
        )
            ->orderBy('nombre', 'asc') // orden alfabético
            ->skip($desde)->take($asta) // desde / asta
            ->get();
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
                //Verificamos contraseña
                if (Hash::check($request->password, $usuario->password)) {
                    // Éxito
                    return response()->json(['estado' => true]);
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
            }

            // No encontrado
            throw new Exception();

            //Errores
        } catch (ValidationException $e) {
            // Validación
            return response()->json([
                'error' => 'Error en la validación, error: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            // Otro
            return response()->json([
                'error' => 'Error desconocido, error: ' . $e->getMessage()
            ], 500);
        }
    }

    //Validar usuario
    public function modificar(Request $request)
    {
        try {
            // Validamos
            $validar = Validator::make($request, [
                'nombre' => 'required|string|min:2|max:255',
                'apellidos' => 'required|string|min:2|max:255',
                'email' => 'required|string|min:5|max:255',
                'newEmail' => 'string|min:4',
                'telefono' => 'required|string|min:10|max:13',
                'password' => 'required|string|min:4',
                'newPassword' => 'string|min:4',
            ]);

            //Error al validar
            if ($validar->fails()) {
                throw new ValidationException();
            }

            // Buscamos usuario
            $usuario = Usuario::where('email', $request->email)->first();

            // Encontrado
            if ($usuario) {
                //Verificamos contraseña
                if (Hash::check($request->password, $usuario->password)) {
                    // Guardamos
                    $usuario->nombre = $request->nombre;
                    $usuario->apellidos = $request->apellidos;
                    $usuario->telefono = $request->telefono;

                    // Email
                    if ($request->newEmail) {
                        $usuario->password = $request->newEmail;
                    }

                    // Contraseña
                    if ($request->newPassword) {
                        $usuario->password = Hash::make($request->newPassword); //Encriptada
                    }

                    $usuario->save();
                }
            }

            // No encontrado
            throw new Exception();

            //Errores
        } catch (ValidationException $e) {
            // Validación
            return response()->json([
                'error' => 'Error en la validación, error: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            // Otro
            return response()->json([
                'error' => 'Error desconocido, error: ' . $e->getMessage()
            ], 500);
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
}
