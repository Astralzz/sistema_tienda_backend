<?php

namespace App\Recursos;

use DateTime;
use Illuminate\Http\Request;

//Funciones globales
class ClaseFunciones
{

    //Creamos la ruta
    public static function crearIdFecha()
    {

        //Obtenemos fecha
        $fechaActual = new DateTime();
        //Convertimos fecha a dígitos
        $fechaActual = $fechaActual->getTimestamp();
        //retornamos
        return $fechaActual;
    }

    //Insertar imagen a una ruta
    public static function guardarArchivo($file, $ruta, $nombreProvisional)
    {
        try {

            //Extension
            $extension = $file->getClientOriginalExtension();
            //Id
            $Id = ClaseFunciones::crearIdFecha();
            //Nombre
            $nombre = "{$nombreProvisional}_{$Id}.{$extension}";
            // Almacenamos la imagen y devolvemos la ruta del archivo almacenado
            return $file->storeAs($ruta, $nombre);
        } catch (\Exception $e) {
            // Si ocurre algún error durante el proceso de almacenamiento, lanzamos una excepción
            throw new \Exception('Error al guardar la imagen: ' . $e->getMessage());
        }
    }
}
