<?php

require_once 'Repositorio.php';
require_once './classes/Reunion.php';



class Repositorio_Reunion extends Repositorio
{
    public function create_reunion($titulo, $fecha, $horaInicio, $HoraFin, $descripcion)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "INSERT INTO r.reuniones ( Titulo, Fecha, HoraInicio, HoraFin, Descripcion) VALUES (?,?,?,?,?)";
        $query = self::$conexion->prepare($sql);

        $query->bind_param("sssss", $titulo, $fecha, $horaInicio, $HoraFin, $descripcion);

        return $query->execute();
    }

    // VER COMO ENCARAR LAS DEMAS FUNCIONES
    public function delete() {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "DELETE * reuniones";
        $query = self::$conexion->prepare($sql);



        return $query->execute();
    }


    public function update(){

    }

    public function getAll(){

    }
}
