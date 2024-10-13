<?php

require_once 'Usuario.php';
class Solicitud
{
    //TipoSolicitud(clave primeria)
    protected $TipoSolicitud;
    //Dni(solicitante clave primaria/secundaria)
    protected $DniSolicitante;
    //FechaDesde
    protected $FechaDesde;
    //FechaHasta
    protected $FechaHasta;

    public function __construct($TipoSolicitud, Usuario $DniSolicitante, $FechaDesde ,$FechaHasta)
    {
        $this->TipoSolicitud = $TipoSolicitud;
        $this->DniSolicitante = $DniSolicitante;
        $this->FechaDesde = $FechaDesde;
        $this->FechaHasta = $FechaHasta;

    }

    // TODO: Getters y setters
    public function getTipoSolicitud()
    {
        return "$this->TipoSolicitud";
    }
    public function getDniSolicitante()
    {
        return $this->DniSolicitante->getDni();
    }
    public function getFechaDesde()
    {
        return $this->FechaDesde;
    }
    public function getFechaHasta()
    {
        return $this->FechaHasta;
    }

}
