<?php

class Evento
{
    protected $nombre;
    protected $fecha;
    protected $departamento;

    public function __construct($nombre, $fecha, Departamento $departamento)
    {
        $this->nombre = $nombre;
        $this->fecha = $fecha;
        $this->departamento = $departamento;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }
}