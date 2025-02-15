<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/AvisoDocumento.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once 'Repositorio_Mensaje.php';

class Repositorio_Archivo extends Repositorio
{
    public function guardarArchivo($nombreArchivo, $fechaCreacion, $contenidoArchivo, $dniCreador)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        // Consulta para insertar archivo binario en la base de datos
        $sql = "INSERT INTO archivos (nombre, FechaCreacion, Contenido, DniCreador) VALUES (?, ?, ?, ?)";

        // Preparar la consulta
        $query = self::$conexion->prepare($sql);

        // Vincula los parámetros
        $query->bind_param("ssss", $nombreArchivo, $fechaCreacion, $contenidoArchivo, $dniCreador);

        // Ejecuto la consulta
        $query->send_long_data(3, $contenidoArchivo);  // Esto es clave para manejar archivos binarios

        $query->execute();

        if ($query->affected_rows > 0) {
            return true;
        }
        return false;
    }

    public function GetAllArchivos($dniCreador = null)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        if ($dniCreador !== null) {
            $sql = "SELECT * FROM archivos WHERE DniCreador= ?";
            $query = self::$conexion->prepare($sql);

            if (!$query) {
                throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
            }


            $query->bind_param('s', $dniCreador);
        } else {


            $sql = "SELECT * FROM archivos ";
            $query = self::$conexion->prepare($sql);


            if (!$query) {
                throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
            }
        }

        $resultado = $query->get_result();
        $archivos = [];

        while ($fila = $resultado->fetch_all()) {
            $archivos[] = $fila;
        }
        $query->close();
        return $archivos;
    }

    public function UpdateArchivo($Contenido, $dniCreador)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "UPDATE archivos set Contenido= ? WHERE DniCreador= ?";
        $query = self::$conexion->prepare($sql);


        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param('ss', $Contenido, $dniCreador);
        // Ejecutar consulta
        if (!$query->execute()) {
            throw new Exception("Error en la ejecución de la consulta: " . $query->error);
        }

        // Verificar cuántas filas fueron afectadas
        if ($query->affected_rows === 0) {
            return false; // No se actualizó ninguna fila (posiblemente el DNI no existe)
        }

        $query->close();
        return true; // Se actualizó correctamente

    }


    public function DeleteArchivo($nombreArchivo, $dniCreador)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "DELETE FROM archivos where Nombre= ? and DniCreador= ?";
        $query = self::$conexion->prepare($sql);

        if (!$query) {
            throw new Exception("Error en la preparacion de la consulta: " . self::$conexion->error);
        }


        $query->bind_param('ss', $nombreArchivo, $dniCreador);

        if (!$query->execute()) {
            throw new Exception("Error en la ejecución de la consulta: " . self::$conexion->error);
        }

        if ($query->affected_rows === 0) {
            $query->close();
            return false; //no se elimino ninguna fila
        }

        $query->close();
        return true; // Se actualizó correctamente


    }
}
