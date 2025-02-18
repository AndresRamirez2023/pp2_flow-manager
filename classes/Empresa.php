<?php

require_once 'Usuario.php';

class Empresa
{
    protected $Nombre;
    protected $DniPrincipal;
    protected $Fondo;
    protected $Logo;
    protected $ArchivoInicio1;
    protected $ArchivoInicio2;

    public function __construct(
        $Nombre,
        $Fondo = null,
        $Logo = null,
        $ArchivoInicio1 = null,
        $ArchivoInicio2 = null,
        $DniPrincipal = null
    ) {
        $this->Nombre = $Nombre;
        $this->Fondo = $Fondo;
        $this->Logo = $Logo;
        $this->ArchivoInicio1 = $ArchivoInicio1;
        $this->ArchivoInicio2 = $ArchivoInicio2;
        $this->DniPrincipal = $DniPrincipal;
    }

    public function getNombre()
    {
        return $this->Nombre;
    }

    public function getDniPrincipal()
    {
        return $this->DniPrincipal;
    }
    public function setFondo($Fondo)
    {
        $this->Fondo = $Fondo;
    }
    public function getFondo()
    {
        return $this->Fondo;
    }
    public function setLogo($Logo)
    {
        $this->Logo = $Logo;
    }
    public function getLogo()
    {
        return $this->Logo;
    }
    public function setArchivoInicio1($ArchivoInicio1)
    {
        $this->ArchivoInicio1 = $ArchivoInicio1;
    }
    public function getArchivoInicio1()
    {
        return $this->ArchivoInicio1;
    }
    public function set($ArchivoInicio2)
    {
        $this->ArchivoInicio2 = $ArchivoInicio2;
    }
    public function getArchivoInicio2()
    {
        return $this->ArchivoInicio2;
    }
}
