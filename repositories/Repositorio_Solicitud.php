<?php
require_once __DIR__ . '/../classes/config.php';
// Incluir clases necesarias
require_once __DIR__ . '/../classes/Usuario.php';
require_once 'Repositorio.php';
class Repositorio_solicitud extends Repositorio
{
    public function guardarSolicitud($TipoSolicitud, $DniSolicitante, $FechaHoraDesde, $FechaHoraHasta)
    {
        $Estado = "pendiente"; // Valor predeterminado para el estado
        $sql = "INSERT INTO solicitudes (TipoSolicitud, DniSolicitante, FechaHoraDesde, FechaHoraHasta, Estado) 
                VALUES (?, ?, ?, ?, ?)";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("sssss", $TipoSolicitud, $DniSolicitante, $FechaHoraDesde, $FechaHoraHasta, $Estado);

        return $query->execute();
    }

    public function updateSolicitud($Estado, $DniSolicitante, $id_licencia)
    {

        // Preparar la consulta SQL
        $sql = "UPDATE solicitudes
      SET Estado = ? WHERE DniSolicitante = ? AND id_licencia = ?";
        $query = self::$conexion->prepare($sql);

        // Asegurarse de que los valores se están pasando correctamente
        $query->bind_param("ssi", $Estado, $DniSolicitante, $id_licencia);

        // Ejecutar la consulta
        $resultado = $query->execute();

        return $resultado;
    }
    public function mostrarSolicitud($DniSolicitante = null)
    {
        $sql = "SELECT TipoSolicitud, DniSolicitante, FechaHoraDesde, FechaHoraHasta, Estado, id_licencia
                FROM solicitudes";

        // Si se pasa un DNI, agregamos el filtro correspondiente
        if ($DniSolicitante) {
            $sql = "SELECT TipoSolicitud, FechaHoraDesde, FechaHoraHasta, Estado, id_licencia FROM solicitudes WHERE DniSolicitante = ?";
        }

        $query = self::$conexion->prepare($sql);

        // Si se pasa un DNI, lo enlazamos al parámetro
        if ($DniSolicitante) {
            $query->bind_param("s", $DniSolicitante);
        }

        if ($query->execute()) {
            $result = $query->get_result();
            $solicitudes = [];
            while ($row = $result->fetch_assoc()) {
                $solicitudes[] = $row;
            }
            return $solicitudes;
        } else {
            return false; // En caso de error
        }
    }
}
