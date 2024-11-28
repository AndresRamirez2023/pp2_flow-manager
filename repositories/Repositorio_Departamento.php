<?php

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/Departamento.php';
require_once __DIR__ . '/../classes/Usuario.php';

class Repositorio_Departamento extends Repositorio
{
    // Verificar si el usuario (director) existe
    public function getUsuarioPorDni($dni)
    {
        $sql = "SELECT DNI FROM Usuarios WHERE DNI = ?";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $dni);

        if ($query->execute()) {
            $query->bind_result($dni_encontrado);
            if ($query->fetch()) {
                return $dni_encontrado;
            }
        }
        return null; // El usuario no existe
    }

    // Guardar el departamento y asignarlo al director
    public function save(Departamento $d)
    {
        $sql = "INSERT INTO Departamentos (nombre, DirectorAcargo) VALUES (?, ?)";
        $query = self::$conexion->prepare($sql);

        $nombre_departamento = $d->getNombre();
        $dni_director = $d->getDirectorAcargo(); // Obtiene el DNI del director

        $query->bind_param("ss", $nombre_departamento, $dni_director);
        return $query->execute();
    }
}
