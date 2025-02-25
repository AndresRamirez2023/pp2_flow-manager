<?php
require_once __DIR__ . '../../classes/Reunion.php';
require_once 'Repositorio.php';
require_once './classes/Reunion.php';
require_once 'Repositorio_Usuario.php';



class Repositorio_Reunion extends Repositorio
{
    public function create(Reunion $r)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "INSERT INTO r.reuniones ( Titulo, Fecha, HoraInicio, HoraFin, Descripcion) VALUES (?,?,?,?,?)";
        $query = self::$conexion->prepare($sql);

        $titulo=$r->getTitulo();
        $fecha=$r->getFecha();
        $horaInicio=$r->getHoraInicio();
        $HoraFin=$r->getHoraFin();
        $descripcion=$r->getDescripcion();



        if (!$query->bind_param("sssss", $titulo, $fecha, $horaInicio, $HoraFin, $descripcion)) {
            echo "Fallo la consulta a la base de datos.";
            return false;
        }

        return $query->execute();
    }

    // VER COMO ENCARAR LAS DEMAS FUNCIONES COMPLETAR LAS QUERYS
    public function delete($dato) {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "DELETE FROM * reuniones WHERE dato";
        $query = self::$conexion->prepare($sql);

        if (!$query) {
            throw new Exception("Error en la rpeparacion de la consulta: " . self::$conexion->error);
        }

        $query->bind_param('s', $dato );



        return $query->execute();
    }

    //Pasar dni del usuario y un id de reunion con un get en donde se realizan las reuniones para asi actualizar la reunion que seleccion?

    public function update(Reunion $r, $usuario ){
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql= "UPDATE reuniones r JOIN usuarios_reuniones u ON r.Fecha = u.FechaReunion set r.Titulo= ?, r.Fecha= ?, r.HoraInicio= ?, r.HoraFin= ?, r.Descripcion= ? WHERE r.id? and u.DniUsuario?";

        $query =self::$conexion->prepare($sql);

        $titulo= $r->getTitulo();
        $fecha>=$r->getFecha();
        $horaInicio=$r->getHoraInicio();
        $HoraFin=$r->getHoraFin();
        $usuario = //dni? 

        /*//if ($usuario_principal) {
            $dni_usuario = $usuario_principal->getDni();
        }//*/

        if (!$query->execute()) {
            throw new Exception("Error en la ejecicion de la actualizacion: " . $query->error);
        }

        if ($query->affected_rows > 0) {
            return true;
        } else {
            return false;
        }

        return $query->execute();
    }

    public function getAll($dato){

        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }


        $Titulo=null;
        $Fecha=null;
        $HoraInicio=null;
        $HoraFin=null;
        $Descripcion=null;

        $sql= 'SELECT *  FROM reuniones where dato';
        $query = self::$conexion->prepare($sql);

        $query->bind_param('s', $dato);
        if ($query->execute()) {
            $query->bind_result($Titulo, $Fecha, $HoraInicio, $HoraFin, $Descripcion);

        $reuniones= [];

        while ($query->fetch()){
            $reuniones[]=[
                'Titulo'=>$Titulo,
                'Fecha'=> $Fecha,
                'HoraInicio'=> $HoraInicio,
                'HoraFin'=>$HoraFin,
                'Descripcion'=>$Descripcion
            ];
        }

        $query->close();
        return !empty($reuniones) ? $reuniones : null;

    }
}

}