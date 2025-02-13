<?php

require_once 'Repositorio.php';
require_once './classes/Reunion.php';



class Repositorio_Reunion extends Repositorio{




    public function create_reunion($titulo, $fecha, $horaInicio, $HoraFin, $descripcion){

        $sql="INSERT INTO r.reuniones (r.Titulo, r.Fecha, r.HoraInicio, r.HoraFin, r.Descripcion)   "

    }
}