<?php

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/Departamento.php';
require_once __DIR__ . '/../classes/Usuario.php';

class Repositorio_Departamento extends Repositorio
{
    // Verificar si el usuario (director) existe
    public function getUsuarioPorDni($dni)
    {
        $sql = "SELECT DNI FROM Usuarios WHERE DNI = ? and TipoDeUsuario = 'Directivo' ";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $dni);

        if ($query->execute()) {
            $query->bind_result($dni_encontrado);
            if ($query->fetch()) {
                return $dni_encontrado;
            }        else{
            print "El usuario no fue encontrado o el dni no pertenece a un directivo";
        }
        return null; // El usuario no existe
    }
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

    public function obtenerDepartamentoPorDni($dni) {
        $sql = "SELECT Departamento FROM Usuarios WHERE Dni = ?";
        $query = self::$conexion->prepare($sql);
        
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }
    
        $query->bind_param("s", $dni); // Asocia el DNI proporcionado al parámetro
    
        if ($query->execute()) {
            $query->bind_result($nombre_departamento);
            if ($query->fetch()) {
                $query->close();
                return $nombre_departamento;
            }
        }
    
        $query->close();
        return null; // Retorna null si no hay resultados
    }


    public function obtenerDirectorACargo($departamento)
    {
        $sql = "SELECT Nombre, Apellido FROM Usuarios WHERE Departamento = ? and TipoDeUsuario='Directivo' ";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $departamento);
    
        if ($query->execute()) {
            $query->bind_result($nombre, $apellido);
            if ($query->fetch()) {
                return $nombre . " " . $apellido; // Devuelve el nombre completo
            }
        }
    
        return null; // El DNI no corresponde a ningún usuario
    }
}