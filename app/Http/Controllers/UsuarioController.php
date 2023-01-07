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
                'nombre' => 'required|string|min:2|max:255',
                'apellidos' => 'required|string|min:2|max:255',
                'email' => 'required|string|min:5|max:255',
                'telefono' => 'required|string|min:10|max:13',
                'password' => 'required|string|min:4',
                'imagen' => 'image|max:2048',
                'isGerente' => 'required|boolean',
            ]);

            // Crear un nuevo usuario
            $usuario = new Usuario();
            $usuario->nombre = $request->nombre;
            $usuario->apellidos = $request->apellidos;
            $usuario->email = $request->email;
            $usuario->telefono = $request->telefono;
            $usuario->password = Hash::make($request->password); //Encriptada
            $usuario->isGerente = $request->isGerente;

            // Se se envió una imagen
            if ($usuario->imagen) {

                // Nombre para imagen
                $nombreImagen = preg_replace('/\s+/', '', $request->nombre . $request->apellidos);

                // Ruta
                $ruta = "public/img/usuarios";

                //Guardamos imagen
                $usuario->imagen = ClaseFunciones::guardarImagen($request->imagen, $ruta, $nombreImagen);
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
            ], 500);
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
    public function validar(Request $request)
    {
        try {
            // Validamos
            $validar = Validator::make($request, [
                'email' => 'required|string|max:255',
                'password' => 'required|string|min:4',
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
                'error' => 'Error en la validación, error: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            // Otro
            return response()->json([
                'error' => 'Error desconocido, error: ' . $e->getMessage()
            ], 500);
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

    //Validar usuarioP
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
}
