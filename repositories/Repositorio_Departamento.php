<?php

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/Departamento.php';
require_once __DIR__ . '/../classes/Usuario.php';

class Repositorio_Departamento extends Repositorio
{
 //OBTIENE EL DEPARTAMENTO AL QUE PERTENECE EL USUARIO LOGUEADO Y LO MUESTRA EN PANTALLA
    public function obtenerDepartamentoPorDni($dni) {


        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }
        $nombre_departamento=null;
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

    //OBTIENE EL DIRECTOR DEL DEPARTAMENTO

    public function obtenerDirectorACargo($departamento)
    {

        $NombreApellido=null;
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT NombreApellido FROM Usuarios WHERE Departamento = ? and TipoDeUsuario='Directivo' ";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $departamento);
    
        if ($query->execute()) {
            $query->bind_result($NombreApellido);
            if ($query->fetch()) {
                return $NombreApellido; // Devuelve el nombre completo
            }
        }
    
        return null; // El DNI no corresponde a ningún usuario
    }


//OBTENER LISTA DEPARTAMENTOS, ESTARA BIEN ASI?

    public function ObtenerListaDepartamento() {

        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT u.NombreApellido, d.nombre AS NombreDepartamento, d.DirectorACargo as dni
                FROM usuarios u
                LEFT JOIN departamentos d ON u.Dni = d.DirectorACargo
                WHERE TipoDeUsuario = 'Directivo' and u.Departamento != 'Sin Departamento'";
        
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

    public function get_all()
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT ";
        $sql .= "d.Nombre, d.Empresa, d.DirectorACargo, e.Nombre, e.usuarioPrincipal ";
        $sql .= "FROM Departamentos d ";
        $sql .= "INNER JOIN Empresas e ON e.Nombre = u.Empresa;";

        $query = self::$conexion->prepare($sql);

        $nombre = null;
        $empresa = null;
        $director_a_cargo = null;
        $nombre_empresa = null;
        $usuario_principal = null;

        if ($query->execute()) {
            $query->bind_result(
                $nombre,
                $empresa,
                $director_a_cargo,
                $nombre_empresa,
                $usuario_principal
            );

            $departamentos = [];
            while ($query->fetch()) {
                $e = new Empresa($nombre, $usuario_principal);
                $departamentos[] = new Departamento($nombre, $director_a_cargo, $e);
            }

            return $departamentos;
        }
    }

    public function create(Departamento $d)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "INSERT INTO departamentos (Nombre, DirectorACargo, Empresa) 
                VALUES (?, ?, ?);";
        $query = self::$conexion->prepare($sql);

        $nombre = $d->getNombre();
        $director_a_cargo = $d->getDirectorAcargo() ? $d->getDirectorAcargo()->getDni() : null;
        $empresa = $d->getEmpresa()->getNombre();

        if (!$query->bind_param("sis", $nombre, $director_a_cargo, $empresa)) {
            echo "Fallo la consulta a la base de datos.";
            return false;
        }

        return $query->execute();
    }

    public function update(Departamento $d)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "UPDATE departamentos SET DirectorACargo = ?, Empresa = ? ";
        $sql .= "WHERE Nombre = ?;";

        $query = self::$conexion->prepare($sql);

        $nombre = $d->getNombre();
        $director_a_cargo = $d->getDirectorAcargo();
        $empresa = $d->getEmpresa();

        if ($director_a_cargo) {
            $dni_usuario = $director_a_cargo->getDni();
        }
        if ($empresa) {
            $nombre_empresa = $empresa->getNombre();
        }
        // Revisar si usuarioPrincipal no da error si es NULL
        if (!$query->bind_param("sis", $dni_usuario, $nombre_empresa, $nombre)) {
            echo "Fallo la consulta a la base de datos.";
            return false;
        }

        return $query->execute();
    }




    public function delete($nombre) {}
}
