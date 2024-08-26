<?php

require_once 'Usuario.php';
class Departamento
{
    // TODO: Atributos:
    // Nombre del Departamento (Clave primaria)
    // DNI del director del departamento

    // TODO: Getters y setters


    protected $nombre;
    protected $DirectorAcargo;



    public function __construct($nombre, Usuario $DirectorAcargo)
    {
        $this->nombre=$nombre;
        $this->DirectorAcargo=$DirectorAcargo;
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

