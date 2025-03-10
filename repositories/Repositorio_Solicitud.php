<?php
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Solicitud.php';
require_once 'Repositorio.php';
class Repositorio_solicitud extends Repositorio
{

    public function create(Solicitud $solicitud)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $tipo_solicitud = $solicitud->getTipoSolicitud();
        $dni_solicitante = $solicitud->getDniSolicitante();
        $fecha_hora_desde = $solicitud->getFechaDesde();
        $fecha_hora_hasta = $solicitud->getFechaHasta();
        $estado = "Pendiente";

        $sql = "INSERT INTO solicitudes (TipoSolicitud, DniSolicitante, FechaHoraDesde, FechaHoraHasta, Estado) 
                VALUES (?, ?, ?, ?, ?)";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("sisss", $tipo_solicitud, $dni_solicitante, $fecha_hora_desde, $fecha_hora_hasta, $estado);

        return $query->execute();
    }

    public function update(Solicitud $solicitud)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $tipo_solicitud = $solicitud->getTipoSolicitud();
        $dni_solicitante = $solicitud->getDniSolicitante();
        $fecha_hora_desde = $solicitud->getFechaDesde();
        $estado = $solicitud->getEstado();

        $sql = "UPDATE solicitudes SET Estado = ? WHERE DniSolicitante = ? AND TipoSolicitud = ? AND FechaHoraDesde = ?";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("siss", $estado, $dni_solicitante, $tipo_solicitud, $fecha_hora_desde);

        return $query->execute();
    }

    public function get_all($dni = null)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT * FROM solicitudes";

        if ($dni) {
            $sql .= " WHERE DniSolicitante = ?";
        }

        $query = self::$conexion->prepare($sql);
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $tipo_solicitud = null;
        $dni_solicitante = null;
        $fecha_hora_desde = null;
        $fecha_hora_hasta = null;
        $estado = null;

        if ($dni) {
            $query->bind_param("i", $dni);
        }

        if ($query->execute()) {
            $query->bind_result(
                $tipo_solicitud,
                $dni_solicitante,
                $fecha_hora_desde,
                $fecha_hora_hasta,
                $estado
            );

            $solicitudes = [];

            while ($query->fetch()) {
                $solicitud = new Solicitud($tipo_solicitud, $dni_solicitante, $fecha_hora_desde, $fecha_hora_hasta, $estado);
                $solicitudes[] = $solicitud;
            }
            return $solicitudes;
        }
        $query->close();
        return null;
    }

    public function delete($id_licencia, $dni_solicitante = null)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "DELETE FROM solicitudes WHERE id_licencia = ?";


        if ($dni_solicitante) {
            $sql = "DELETE FROM solicitudes WHERE id_licencia = ? and  DniSolicitante=?";
        }

        $query = self::$conexion->prepare($sql);
        // Si se pasa un DNI, lo enlazamos ambos parametros
        if ($dni_solicitante) {
            $query->bind_param("ss", $id_licencia, $dni_solicitante);
        } else {
            //Si no se pasa un DNI, Solo se enlaza el id_licencia
            $query->bind_param("s", $id_licencia);
        }

        //Ejecutamos la consulta

        $query->execute();

        // Comprobamos si la ejecucion fue exitosa

        if ($query->affected_rows > 0) {

            // Si funciono
            echo "Solicitud eliminada con exito.";
        } else {
            // si no funciono (pincho)
            echo "No se encotro ninguna solicitud para eliminar";
        }
    }
}
