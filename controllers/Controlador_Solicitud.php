<?php
require_once __DIR__ . '/../repositories/Repositorio_solicitud.php';

class Controlador_Solicitud
{
    protected $repositorio;

    public function __construct()
    {
        // Crear una instancia del repositorio de solicitudes
        $this->repositorio = new Repositorio_solicitud();
    }

    // Obtener todas las solicitudes o las solicitudes de un usuario específico
    public function obtenerSolicitudes($dni = null)
    {
        return $this->repositorio->mostrarSolicitud($dni);
    }

    // Crear una solicitud de licencia
    public function crearSolicitud($tipoLicencia, $DniSolicitante, $fechaInicio, $fechaFin)
    {
        // Validar las fechas antes de proceder
        if ($this->validarFechas($fechaInicio, $fechaFin)) {
            return $this->repositorio->guardarSolicitud($tipoLicencia, $DniSolicitante, $fechaInicio, $fechaFin);
        } else {
            return "La fecha de inicio debe ser anterior o igual a la fecha de fin.";
        }
    }

    // Actualizar el estado de una solicitud
    public function actualizarEstado($estado, $DniSolicitante, $id_licencia)
    {
        return $this->repositorio->updateSolicitud($estado, $DniSolicitante, $id_licencia);
    }

    // Eliminar una solicitud
    public function eliminarSolicitud($id_licencia, $DniSolicitante = null)
    {
        return $this->repositorio->deleteSolicitud($id_licencia, $DniSolicitante);
    }

    // Validar que la fecha de inicio sea menor o igual a la fecha de fin
    private function validarFechas($fechaInicio, $fechaFin)
    {
        return $fechaInicio <= $fechaFin;
    }
}
