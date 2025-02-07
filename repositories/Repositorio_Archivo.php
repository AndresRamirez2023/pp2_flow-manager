<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/AvisoDocumento.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once 'Repositorio_Mensaje.php';

class Repositorio_Archivo extends Repositorio
{
    // Constructor para inicializar la conexión

    
    public function guardarArchivo($nombreArchivo, $fechaCreacion, $contenidoArchivo, $dniCreador)
{
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

}

