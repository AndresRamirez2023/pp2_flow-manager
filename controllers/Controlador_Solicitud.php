<?php
require_once __DIR__ . '/../repositories/Repositorio_solicitud.php';

class Controlador_Solicitud
{
    protected $rs;

    public function __construct()
    {
        $this->rs = new Repositorio_solicitud();
    }

    public function get_all($dni = null)
    {
        return $this->rs->get_all($dni);
    }

    // Crear una solicitud de licencia
    public function create(Solicitud $solicitud)
    {
        try {
            // Validar las fechas antes de proceder
            $this->validarFechas($solicitud->getFechaDesde(), $solicitud->getFechaHasta());
            return $this->rs->create($solicitud);
        } catch (Exception $e) {
            $_SESSION['mensaje'] = $e->getMessage();
            $_SESSION['mensaje_tipo'] = "danger";
            return false;
        }
    }

    // Actualizar el estado de una solicitud
    public function update($solicitud)
    {
        return $this->rs->update($solicitud);
    }

    // Eliminar una solicitud
    public function delete($id_licencia, $DniSolicitante = null)
    {
        return $this->rs->delete($id_licencia, $DniSolicitante);
    }

    // Validar que la fecha de inicio sea menor o igual a la fecha de fin
    private function validarFechas($fechaInicio, $fechaFin)
    {
        if (!($fechaInicio <= $fechaFin)) {
            throw new Exception("La <b>fecha de inicio</b> debe ser anterior o igual a la <b>fecha de fin</b>.");
        }
    }
}
