<?php

require_once 'Usuario.php';
class Departamento
{
    protected $nombre;
    protected $directorAcargo;
    protected $empresa;

    public function __construct($nombre, Usuario $directorAcargo = null, Empresa $empresa)
    {
        $this->nombre = $nombre;
        $this->directorAcargo = $directorAcargo;
        $this->empresa = $empresa;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDirectorAcargo()
    {
        return $this->directorAcargo;
    }

    public function setDirectorAcargo(Usuario $director)
    {
        $this->directorAcargo = $director;
    }

    public function setEmpresa(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }
    public function getEmpresa()
    {
        return $this->empresa;
    }
}
