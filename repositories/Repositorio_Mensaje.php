<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Repositorio.php';
require_once 'Repositorio_Archivo.php';
require_once __DIR__ . '/../classes/Mensaje.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Solicitud.php';


class Repositorio_Mensaje extends Repositorio_Archivo {

    public function redactar_mensaje($FechaHoraMensaje, $TituloMensaje, $DniRemitente, $TipoMensaje, $CuerpoMensaje, $DniReceptor, $archivo = null) {

        $sql_dni = "SELECT Dni from usuarios where Dni=?";
        $query_dni = self::$conexion->prepare($sql_dni);
        $query_dni->bind_param("s", $DniReceptor);
        $query_dni->execute();
        $resultado_dni = $query_dni->get_result();

        if ($resultado_dni->num_rows == 0) {
            exit("El DNI del receptor no fue encontrado");
        }

        $sql = 'INSERT INTO mensajes (FechaHoraMensaje, TituloMensaje, DniRemitente, TipoMensaje, CuerpoMensaje, DniReceptor) VALUES (?, ?, ?, ?, ?, ?)';
        $query = self::$conexion->prepare($sql);
        $query->bind_param("ssssss", $FechaHoraMensaje, $TituloMensaje, $DniRemitente, $TipoMensaje, $CuerpoMensaje, $DniReceptor);

        if ($query->execute()) {

            if ($archivo !== null) {
                // Asegurarse de que el archivo fue recibido correctamente
                if ($archivo['error'] == UPLOAD_ERR_OK) {
                    // Obtener los detalles del archivo
                    $contenidoArchivo = file_get_contents($archivo['tmp_name']);
                    $nombreArchivo = $archivo['name'];
                    $fechaCreacion = date("Y-m-d H:i:s"); // Fecha actual
                    $dniCreador = $DniRemitente; // DNI del creador del mensaje
    
                    // Invocar la función para guardar el archivo
                    $this->guardarArchivo($nombreArchivo, $fechaCreacion, $contenidoArchivo, $dniCreador);
                } else {
                    exit("Hubo un problema al subir el archivo.");
                }
            }
        }
    }
}

