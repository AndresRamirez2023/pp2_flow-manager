<?php
 require_once __DIR__ . '/../classes/config.php';
 // Incluir clases necesarias
 require_once __DIR__ . '/../classes/Usuario.php';
 require_once 'Repositorio.php';
class Repositorio_solicitud extends Repositorio
{
    public function guardarSolicitud($TipoSolicitud, $DniSolicitante, $FechaHoraDesde, $FechaHoraHasta)
    {
        $estado = "pendiente"; // Valor predeterminado para el estado
        $sql = "INSERT INTO solicitudes (TipoSolicitud, DniSolicitante, FechaHoraDesde, FechaHoraHasta, Estado) 
                VALUES (?, ?, ?, ?, ?)";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("sssss", $TipoSolicitud, $DniSolicitante, $FechaHoraDesde, $FechaHoraHasta, $Estado);
    
        return $query->execute();
    }
    

    public function mostrarSolicitud($DniSolicitante = null) {
        $sql = "SELECT TipoSolicitud, FechaHoraDesde, FechaHoraHasta, estado
                FROM solicitudes";
        
        // Si se pasa un DNI, agregamos el filtro correspondiente
        if ($DniSolicitante) {
            $sql = "SELECT TipoSolicitud, FechaHoraDesde, FechaHoraHasta, Estado FROM solicitudes WHERE DniSolicitante = ?";
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