<?php
 require_once __DIR__ . './../.env.php';
 // Incluir clases necesarias
 require_once __DIR__ . '/../classes/Usuario.php';
 require_once 'Repositorio.php';
class Repositorio_solicitud extends Repositorio
{
    public function guardarSolicitud($TipoSolicitud, $DniSolicitante, $FechaHoraDesde, $FechaHoraHasta)
    {
        $sql = "INSERT INTO solicitudes (TipoSolicitud, DniSolicitante, FechaHoraDesde, FechaHoraHasta) VALUES (?, ?, ?, ?)";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("ssss", $TipoSolicitud, $DniSolicitante, $FechaHoraDesde, $FechaHoraHasta);

        return $query->execute();
    }


public function mostrarSolicitud($DniSolicitante)
// Falta agreagar ESTADO. 
{
    $sql = "SELECT TipoSolicitud, FechaHoraDesde, FechaHoraHasta 
            FROM solicitudes 
            WHERE DniSolicitante = ?";
    $query = self::$conexion->prepare($sql);
    $query->bind_param("s", $DniSolicitante);

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