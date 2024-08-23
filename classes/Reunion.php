
<?php
class Reunion
{
    // TODO: Atributos:
    // Fecha de la reunión (Clave primaria)
    // Hora de inicio de la reunión (Clave primaria)
    // Hora de fin de la reunión (Clave primaria)
    // Tema a tratar en la reunión
    // Descripción opcional

    // TODO: Getters y setters

    //Tema
    protected $tema;
    //Fecha(Clave Primaria)
    protected $fecha;
    //HoraInicio(Clave Primaria)
    protected $horaInicio;
    //HoraFin(Clave primaria)
    protected $horaFin
    //Descripcion
    protected $descripcion


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
        return $this->nombre;
    }
    public function getHoraInicio()
    {
        return $this->apellido;
    }
    public function getHoraFin()
    {
        return $this->email;
    }
    public function getDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }


    
}




