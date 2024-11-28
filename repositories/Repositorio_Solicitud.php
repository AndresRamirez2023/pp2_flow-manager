<?php
 require_once __DIR__ . '/../classes/config.php';
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
}
