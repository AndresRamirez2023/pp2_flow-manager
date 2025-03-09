<?php

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/Departamento.php';
require_once __DIR__ . '/../classes/Usuario.php';

class Repositorio_Departamento extends Repositorio
{
    //OBTIENE EL DEPARTAMENTO AL QUE PERTENECE EL USUARIO LOGUEADO Y LO MUESTRA EN PANTALLA
    public function obtenerDepartamentoPorDni($dni)
    {


        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }
        $nombre_departamento = null;
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
        $NombreApellido = null;
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT NombreApellido FROM Usuarios WHERE Departamento = ? and TipoDeUsuario='Directivo' ";
        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $departamento);

        if ($query->execute()) {
            $query->bind_result($NombreApellido);
            if ($query->fetch()) {
                return $NombreApellido;
            }
        }
        return null;
    }

    public function get_all($nombre_empresa)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT ";
        $sql .= "d.Nombre, d.Empresa, d.DirectorACargo, u.CorreoElectronico, u.TipoDeUsuario, u.NombreApellido ";
        $sql .= "FROM Departamentos d ";
        $sql .= "LEFT JOIN Usuarios u ON u.Dni = d.DirectorACargo WHERE d.Empresa = ?;";

        $query = self::$conexion->prepare($sql);

        $nombre = null;
        $empresa = null;
        $director_a_cargo = null;
        $correo_electronico = null;
        $tipo_de_usuario = null;
        $nombre_apellido = null;

        $query->bind_param('s', $nombre_empresa);

        if ($query->execute()) {
            $query->bind_result(
                $nombre,
                $empresa,
                $director_a_cargo,
                $correo_electronico,
                $tipo_de_usuario,
                $nombre_apellido
            );

            $departamentos = [];
            while ($query->fetch()) {
                $e = new Empresa($empresa);
                $u = $director_a_cargo ? new Usuario($director_a_cargo, $correo_electronico, $tipo_de_usuario, null, $nombre_apellido) : null;
                $departamentos[] = new Departamento($nombre, $u, $e);
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
        if (!$query->bind_param("iss", $dni_usuario, $nombre_empresa, $nombre)) {
            echo "Fallo la consulta a la base de datos.";
            return false;
        }

        return $query->execute();
    }

    public function delete($nombre)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "DELETE FROM departamentos WHERE Nombre = ?;";
        $query = self::$conexion->prepare($sql);

        if (!$query->bind_param("s", $nombre)) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        return $query->execute();
    }

    public function get_by_name($nombre)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $nombre_departamento = null;
        $nombre_empresa = null;
        $director_a_cargo = null;
        $dni = null;
        $nombre_apellido = null;
        $fecha_nacimiento = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_de_usuario = null;
        $departamento_usuario = null;
        $clave_encriptada = null;

        $newSql = "SELECT * FROM Departamentos d LEFT JOIN Usuarios u ON u.Dni = d.directorACargo WHERE d.Nombre = ? LIMIT 1;";

        $query = self::$conexion->prepare($newSql);
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param("s", $nombre);

        if ($query->execute()) {

            $query->bind_result(
                $nombre_departamento,
                $nombre_empresa,
                $director_a_cargo,
                $dni,
                $nombre_apellido,
                $fecha_nacimiento,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_de_usuario,
                $departamento_usuario,
                $clave_encriptada
            );

            if ($query->fetch()) {
                $e = new Empresa($nombre_empresa);
                $u = new Usuario($dni, $correo_electronico, $tipo_de_usuario, null, $nombre_apellido);

                $query->close();
                return new Departamento(
                    $nombre_departamento,
                    $u,
                    $e
                );
            }
        }
        $query->close();
        return null;
    }
}
