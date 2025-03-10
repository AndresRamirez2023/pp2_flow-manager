<?php

require_once 'Usuario.php';
class Solicitud
{
    protected $TipoSolicitud;
    protected $DniSolicitante;
    protected $FechaDesde;
    protected $FechaHasta;
    protected $estado;

    public function __construct($TipoSolicitud, $DniSolicitante, $FechaDesde, $FechaHasta, $estado)
    {
        $this->TipoSolicitud = $TipoSolicitud;
        $this->DniSolicitante = $DniSolicitante;
        $this->FechaDesde = $FechaDesde;
        $this->FechaHasta = $FechaHasta;
        $this->estado = $estado;
    }
    public function getTipoSolicitud()
    {
        return "$this->TipoSolicitud";
    }
    public function getDniSolicitante()
    {
        return $this->DniSolicitante;
    }
    public function getFechaDesde()
    {
        return $this->FechaDesde;
    }
    public function getFechaHasta()
    {
        return $this->FechaHasta;
    }
    public function getEstado()
    {
        return $this->estado;
    }
}
