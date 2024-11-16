<?php

require_once '.env.php';
require_once 'Departamento.php';
require_once 'Repositorio.php';



class Repositorio_Departamento extends Repositorio
{

    public function get_all($dni_usuario = null)
    {
        $sql = "SELECT ";
        $sql .= "d.Nombre, d.DirectorAcargo, u.DNI ";
        $sql .= "FROM Departamentos d ";
        $sql .= "INNER JOIN Usuarios u ON d.DirectorACargo = u.Dni ";

        if ($dni_usuario) {
            $sql .= "WHERE u.DNI = ? ";
        }

        $sql .= "ORDER BY d.nombre;";

        // var_dump($sql); die();
        $query = self::$conexion->prepare($sql);

        if ($dni_usuario) {
            // Si el DNI NO es nulo, relacionamos $DNI con el parámetro"?"
            $query->bind_param("s", $dni_usuario);
        }

        if ($query->execute()) {

            $query->bind_result(
                $nombre_departamento,
                $director_acargo,
                $dni_usuario
            );

            $departamentos = [];

            while ($query->fetch()) {
                $d = new Departamento($nombre_departamento, $director_acargo, $dni_usuario);

                $departamentos[] = $d;
            }
            return $departamentos;
        }
    }





    public function create(Departamento $d)
    {

        // Preparamos la query del update
        $sql = "INSERT INTO departamento(nombre, DirectorAcargo) VALUES (?, ?);";
        $query = self::$conexion->prepare($sql);

        // Obtenemos los nuevos valores desde el objeto:
        $nombre = $d->getNombre();
        $DirectorAcargo = $d->getDirectorAcargo();

        // Asignamos los valores para reemplazar los "?" en la query
        if (!$query->bind_param("ss", $nombre, $DirectorAcargo->getDirectorAcargo())) {
            echo "fallo la consulta";
        }

        // Retornamos true si la query tiene éxito, false si fracasa
        return $query->execute();
    }

    // CONFLICTO CON DELETE/UPTDATE RELACIONES CON LA TABLA USUARIO----------- VER QUE HACER, DELETE ON CASCADE? UPDATE ON CASCADE?


    public function agregar_empleado_departamento()
    {
        $sql = "INSERT INTO Empleados (dni, nombre, departamento_id) VALUES (?, ?, ?);";
        $query = self::$conexion->prepare($sql);
        if (!$query->bind_param("ssi", $dni, $nombre, $departamentoId)) {
            echo "Error al agregar el empleado";
        }
        return $query->execute();
    }


    public function agregar_evento_a_departamento($nombre_evento, $fecha_evento, $nombre_departamento)
    {
        $sql = "INSERT INTO eventos (nombre, fecha, departamento) VALUES (?, ?, ?)";
        $query = self::$conexion->prepare($sql);

        if (!$query->bind_param("sss", $nombre_evento, $fecha_evento, $nombre_departamento)) {
            echo "Error al preparar la consulta";
        }

        return $query->execute();
    }


    public function obtener_eventos_departamento($departamento_id)
    {
        $sql = "SELECT nombre_evento, fecha_evento FROM eventos_departamento WHERE departamento_id = ?";
        $query = self::$conexion->prepare($sql);

        // Enlazar el parámetro a la consulta
        if (!$query->bind_param("i", $departamento_id)) {
            echo "Error al preparar la consulta.";
            return false;
        }

        // Ejecutar la consulta
        if ($query->execute()) {
            $result = $query->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); // Obtener todos los eventos como array asociativo
        } else {
            echo "Error al ejecutar la consulta.";
            return false;
        }
    }
}

