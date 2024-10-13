<?php
class Reunion
{

    //Tema
    protected $tema;
    //Fecha(Clave Primaria)
    protected $fecha;
    //HoraInicio(Clave Primaria)
    protected $horaInicio;
    //HoraFin(Clave primaria)
    protected $horaFin;
    //Descripcion
    protected $descripcion;


    public function __construct($tema, $fecha, $horaInicio, $horaFin, $descripcion)
    {
        $this->tema = $tema;
        $this->fecha = $fecha;
        $this->horaInicio = $horaInicio;
        $this->horaFin = $horaFin;
        $this->descripcion = $descripcion;
    }


    public function GetTema()
    {
        return "$this->tema";
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




