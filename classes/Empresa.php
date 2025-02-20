<?php
require_once 'Usuario.php';

class Empresa
{
    protected $nombre;
    protected $usuarioPrincipal;
    protected $fondo;
    protected $logo;
    protected $archivoInicio1;
    protected $archivoInicio2;

    public function __construct(
        $nombre,
        $fondo = null,
        $logo = null,
        $archivoInicio1 = null,
        $archivoInicio2 = null,
        Usuario $usuarioPrincipal = null
    ) {
        $this->nombre = $nombre;
        $this->fondo = $fondo;
        $this->logo = $logo;
        $this->archivoInicio1 = $archivoInicio1;
        $this->archivoInicio2 = $archivoInicio2;
        $this->usuarioPrincipal = $usuarioPrincipal;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getUsuarioPrincipal()
    {
        return $this->usuarioPrincipal;
    }
    public function setUsuarioPrincipal(Usuario $usuarioPrincipal)
    {
        $this->usuarioPrincipal = $usuarioPrincipal;
    }
    public function setFondo($fondo)
    {
        $this->fondo = $fondo;
    }
    public function getFondo()
    {
        return $this->fondo;
    }
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
    public function getLogo()
    {
        return $this->logo;
    }
    public function setArchivoInicio1($archivoInicio1)
    {
        $this->archivoInicio1 = $archivoInicio1;
    }
    public function getArchivoInicio1()
    {
        return $this->archivoInicio1;
    }
    public function setArchivoInicio2($archivoInicio2)
    {
        $this->archivoInicio2 = $archivoInicio2;
    }
    public function getArchivoInicio2()
    {
        return $this->archivoInicio2;
    }
}
