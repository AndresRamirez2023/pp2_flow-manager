<?php

require_once 'Usuario.php';
class Reunion
{

    //Titulo
    protected $titulo;
    //Fecha(Clave Primaria)
    protected $fecha;
    //HoraInicio(Clave Primaria)
    protected $horaInicio;
    //HoraFin(Clave primaria)
    protected $horaFin;
    //Descripcion
    protected $descripcion;


    public function __construct($titulo, $fecha, $horaInicio, $horaFin, $descripcion)
    {
        $this->titulo = $titulo;
        $this->fecha = $fecha;
        $this->horaInicio = $horaInicio;
        $this->horaFin = $horaFin;
        $this->descripcion = $descripcion;
    }


    public function getTitulo()
    {
        return "$this->titulo";
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }
    public function getHoraFin()
    {
        return $this->horaFin;
    }
    public function getDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

}




