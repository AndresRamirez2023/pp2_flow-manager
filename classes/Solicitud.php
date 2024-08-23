<?php
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

    // TODO: Atributos:
    // Tipo de Solicitud (Clave primaria)
    // Fecha de la Solicitud (Clave primaria)
    // DNI del usuario (Clave primaria)
    // Fecha desde cuando inicia la solicitud
    // Fecha de hasta cuando es de la solicitud

    public function __construct($TipoSolicitud, $DniSolicitante, $FechaDesde ,$FechaHasta)
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



}
