<?php

require_once 'Usuario.php';
class Departamento
{
    protected $nombre;
    protected $DirectorAcargo;



    public function __construct($nombre, Usuario $DirectorAcargo)
    {
        $this->nombre = $nombre;
        $this->DirectorAcargo = $DirectorAcargo;
    }


    public function getNombre()
    {
        return "$this->nombre";
    }

    public function getDirectorAcargo()
    {
        return $this->DirectorAcargo->getDni();
    }







}

