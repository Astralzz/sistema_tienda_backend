<?php

namespace App\Recursos;

use DateTime;
use Illuminate\Http\Request;

//Funciones globales
class ClaseFunciones
{

    //Creamos la ruta
    private function crearNombreImg($nombre)
    {
        //Creamos nombre
        //Obtenemos fecha
        $fechaActual = new DateTime();
        //Convertimos fecha a dígitos
        $fechaActual = $fechaActual->getTimestamp();
        //creamos nombre nuevo
        $nombreNuevo = $nombre . '_' . $fechaActual;

        //retornamos
        return $nombreNuevo;
    }

    //Insertar imagen a una ruta
    public static function guardarImagen($imagen, $ruta, $nombreUsuario)
    {
        // Primero, verificamos si el archivo subido es realmente una imagen
        // utilizando la función getimagesize de PHP
        $esImagen = getimagesize($imagen);

        //Éxito
        if ($esImagen !== false) {
            try {
                // Almacenamos la imagen y devolvemos la ruta del archivo almacenado
                return $imagen->storeAs($ruta, ClaseFunciones::crearNombreImg($nombreUsuario) . $imagen->extension());
            } catch (\Exception $e) {
                // Si ocurre algún error durante el proceso de almacenamiento, lanzamos una excepción
                throw new \Exception('Error al guardar la imagen: ' . $e->getMessage());
            }
        } else {
            // Si el archivo no es una imagen, lanzamos una excepción
            throw new \Exception('El archivo subido no es una imagen');
        }
    }
}
