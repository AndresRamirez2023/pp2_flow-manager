<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Repositorio.php';
require_once 'Repositorio_Archivo.php';
require_once __DIR__ . '/../classes/Mensaje.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Solicitud.php';


class Repositorio_Mensaje extends Repositorio_Archivo
{

    public function redactar_mensaje($FechaHoraMensaje, $TituloMensaje, $DniRemitente, $TipoMensaje, $CuerpoMensaje, $DniReceptor, $archivo = null)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

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

    public function GetAllMensajes($DniReceptor)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $FechaHoraMensaje = null;
        $TituloMensaje = null;
        $DniRemitente = null;
        $TipoMensaje = null;
        $CuerpoMensaje = null;

        $sql = 'SELECT FechaHoraMensaje, TituloMensaje, DniRemitente, TipoMensaje, CuerpoMensaje FROM mensajes where DniReceptor= ?';
        $query = self::$conexion->prepare($sql);


        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param('s', $DniReceptor);
        if ($query->execute()) {
            $query->bind_result($FechaHoraMensaje, $TituloMensaje, $DniRemitente, $TipoMensaje, $CuerpoMensaje);

            $mensajes = [];

            while ($query->fetch()) {
                $mensajes[] = [
                    'FechaHoraMensaje' => $FechaHoraMensaje,
                    'TituloMensaje' => $TituloMensaje,
                    'DniRemitente' => $DniRemitente,
                    'TipoMensaje' => $TipoMensaje,
                    'CuerpoMensaje' => $CuerpoMensaje

                ];
            }
        }


        $query->close();
        return !empty($mensajes) ? $mensajes : null;
    }


    public function UpdateMensajes($DniReceptor, $nuevoTitulo, $nuevoTipo, $nuevoCuerpo)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "UPDATE mensajes set  TituloMensaje=?, TipoMensaje=?, CuerpoMensaje=? WHERE DniReceptor=?";
        $query = self::$conexion->prepare($sql);

        if (!$query) {
            throw new Exception("Error en la rpeparacion de la consulta: " . self::$conexion->error);
        }

        $query->bind_param('ssss', $nuevoTitulo, $nuevoTipo, $nuevoCuerpo, $DniReceptor);

        if (!$query->execute()) {
            throw new Exception("Error en la ejecicion de la actualizacion: " . $query->error);
        }

        if ($query->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function DeleteMensajes($TituloMensaje, $DniReceptor)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "DELETE * FROM mensajes WHERE TituloMensaje= ? and DniReceptor=?";
        $query = self::$conexion->prepare($sql);

        if (!$query) {
            throw new Exception("Error en la rpeparacion de la consulta: " . self::$conexion->error);
        }

        $query->bind_param("ss", $TituloMensaje, $DniReceptor);

        $resultado = $query->execute();

        if (!$resultado) {
            error_log("Error al ejecutar la consulta: " . $query->error);
            return false;
        }

        return true; // Retorna true si se eliminó correctamente
    }
}
