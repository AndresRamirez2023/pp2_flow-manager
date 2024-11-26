<?php

require_once 'Usuario.php';
class Departamento
{
    protected $nombre;
    protected $DirectorAcargo; // Es correcto, pero mantén la consistencia

    // Constructor, el director puede ser null
    public function __construct($nombre, Usuario $DirectorAcargo = null)
    {
        $this->nombre = $nombre;
        $this->DirectorAcargo = $DirectorAcargo;
    }

    // Obtener el nombre del departamento
    public function getNombre()
    {
        return $this->nombre;
    }

    // Obtener el DNI del director, si existe
    public function getDirectorAcargo()
    {
        // Verificar si el director existe antes de intentar obtener el DNI
        return $this->DirectorAcargo ? $this->DirectorAcargo->getDni() : 'Sin director';
    }

    // Establecer el director a cargo
    public function setDirectorAcargo(Usuario $director)
    {
        $this->DirectorAcargo = $director;
    }
}





