<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/Mensaje.php';
require_once __DIR__ . '/../classes/Usuario.php';


class Repositorio_Mensaje extends Repositorio
{

    public function send(Mensaje $mensaje)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $fecha_hora_mensaje = $mensaje->getFechaHora();
        $titulo_mensaje = $mensaje->getTituloMensaje();
        $dni_remitente = $mensaje->getRemitente();
        $tipo_mensaje = $mensaje->getTipoMensaje();
        $cuerpo_mensaje = $mensaje->getCuerpoMensaje();
        $dni_receptor = $mensaje->getReceptor();
        $requiere_firma = $mensaje->getRequiereFirma();
        $path_archivo = $mensaje->getPathArchivo();

        $sql = 'INSERT INTO
        mensajes (FechaHoraMensaje, TituloMensaje, DniRemitente, TipoMensaje, CuerpoMensaje, DniReceptor, RequiereFirma, PathArchivo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $query = self::$conexion->prepare($sql);

        if (!$query->bind_param(
            "ssississ",
            $fecha_hora_mensaje,
            $titulo_mensaje,
            $dni_remitente,
            $tipo_mensaje,
            $cuerpo_mensaje,
            $dni_receptor,
            $requiere_firma,
            $path_archivo
        )) {
            echo "Fallo la consulta a la base de datos.";
            return false;
        }

        return $query->execute();
    }

    public function get_all($dni, $tipo)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }
        $sql = 'SELECT * FROM mensajes';

        $query = self::$conexion->prepare($sql);
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $fecha_hora_mensaje = null;
        $titulo_mensaje = null;
        $dni_remitente = null;
        $tipo_mensaje = null;
        $cuerpo_mensaje = null;
        $dni_receptor = null;
        $requiere_firma = null;
        $path_archivo = null;

        if ($tipo) {
            if ($tipo == 'remitente') {
                $sql .= " WHERE DniRemitente = ?;";
            } elseif ($tipo == 'receptor') {
                $sql .= "  WHERE DniReceptor = ?;";
            }
            $query->bind_param('i', $dni);
        }

        if ($query->execute()) {
            $query->bind_result(
                $fecha_hora_mensaje,
                $titulo_mensaje,
                $dni_remitente,
                $tipo_mensaje,
                $cuerpo_mensaje,
                $dni_receptor,
                $requiere_firma,
                $path_archivo
            );

            $mensajes = [];

            while ($query->fetch()) {
                $mensaje = new Mensaje(
                    $fecha_hora_mensaje,
                    $titulo_mensaje,
                    $dni_remitente,
                    $tipo_mensaje,
                    $cuerpo_mensaje,
                    $dni_receptor,
                    $requiere_firma,
                    $path_archivo
                );
                $mensajes[] = $mensaje;
            }
            return $mensajes;
        }
        $query->close();
        return null;
    }

    public function delete($TituloMensaje, $DniReceptor)
    {
        // TODO:
    }
}
