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

    public function ObtenerListaDepartamento() {
        $sql = "SELECT concat (u.nombre, ' ', u.apellido) as nombre, d.nombre AS NombreDepartamento, d.DirectorACargo as dni
                FROM usuarios u
                LEFT JOIN departamentos d ON u.Dni = d.DirectorACargo
                WHERE TipoDeUsuario = 'Directivo'";
        
        $query = self::$conexion->prepare($sql);
    
        if ($query->execute()) {
            $result = $query->get_result();
            $listaDepartamento = []; // Declaración del array
    
            while ($fila = $result->fetch_assoc()) {
                $listaDepartamento[] = [
                    'nombreDepartamento' => $fila['NombreDepartamento'],
                    'nombreDirector' => $fila['nombre'],
                    'dniDirector' => $fila['dni']                   
                    
                ];
            }
    
            // Aquí devuelves todo el array acumulado
            return $listaDepartamento;
        }
    
        // En caso de fallo de la consulta, devuelves un array vacío
        return [];
    }
    public function actualizarDepartamento($nombreNuevo, $dniViejo, $dniNuevo) {
        $sql = "UPDATE departamentos SET Nombre = ?, DirectorACargo = ? WHERE DirectorACargo = ?";
        $query = self::$conexion->prepare($sql);
        return $query->execute([$nombreNuevo, $dniNuevo, $dniViejo]);
    }
    

    public function verificarDniEnUso($dni) {
        $sql = "SELECT COUNT(*) FROM departamentos WHERE DirectorACargo = ?";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $dni);
        $query->execute();
        $query->bind_result($count);
        $query->fetch();
        $query->close();
    
        // Si el resultado es mayor que 0, el DNI ya está en uso
        return $count > 0;
    }
    
    public function EliminarDepartamento($nombre_departamento) {
        $sql = "DELETE FROM DEPARTAMENTOS WHERE Nombre = ?";
        $query = self::$conexion->prepare($sql);
    
        if (!$query) {
            error_log("Error en la preparación de la consulta: " . self::$conexion->error);
            return false;
        }
    
        $query->bind_param("s", $nombre_departamento);
        $resultado = $query->execute();
    
        if (!$resultado) {
            error_log("Error al ejecutar la consulta: " . $query->error);
            return false;
        }
    
        return true; // Retorna true si se eliminó correctamente
    }

}