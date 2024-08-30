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
}