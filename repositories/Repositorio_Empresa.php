<?php
require_once __DIR__ . '../../classes/Empresa.php';
require_once 'Repositorio_Usuario.php';
require_once 'Repositorio.php';

class Repositorio_Empresa extends Repositorio
{

    public function get_all()
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT ";
        $sql .= "e.Nombre, e.usuarioPrincipal, e.Fondo, e.Logo, e.ArchivoInicio1, e.ArchivoInicio2, u.CorreoElectronico, u.TipoDeUsuario, u.NombreApellido ";
        $sql .= "FROM Empresas e ";
        $sql .= "LEFT JOIN usuarios u ON u.Dni = e.UsuarioPrincipal;";

        $query = self::$conexion->prepare($sql);

        $nombre = null;
        $dni_usuario_principal = null;
        $fondo = null;
        $logo = null;
        $archivo_inicio1 = null;
        $archivo_inicio2 = null;
        $correo_electronico = null;
        $tipo_de_usuario = null;
        $nombre_apellido = null;

        if ($query->execute()) {
            $query->bind_result(
                $nombre,
                $dni_usuario_principal,
                $fondo,
                $logo,
                $archivo_inicio1,
                $archivo_inicio2,
                $correo_electronico,
                $tipo_de_usuario,
                $nombre_apellido
            );

            $empresas = [];
            while ($query->fetch()) {
                $u = new Usuario($dni_usuario_principal, $correo_electronico, $tipo_de_usuario, null, $nombre_apellido);
                $empresas[] = new Empresa($nombre, $u, $fondo, $logo, $archivo_inicio1, $archivo_inicio2);
            }

            return $empresas;
        }
    }

    public function get_by_name($nombre)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT e.UsuarioPrincipal, u.CorreoElectronico, u.TipoDeUsuario, u.NombreApellido FROM Empresas e  LEFT JOIN usuarios u ON u.Dni = e.UsuarioPrincipal WHERE Nombre = ? LIMIT 1;";

        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $nombre);

        $usuario_principal = null;
        $correo_electronico = null;
        $tipo_de_usuario = null;
        $nombre_apellido = null;

        if ($query->execute()) {
            $query->bind_result($usuario_principal, $correo_electronico, $tipo_de_usuario, $nombre_apellido);
            if ($query->fetch()) {
                $u = new Usuario($usuario_principal, $correo_electronico, $tipo_de_usuario, null, $nombre_apellido);
                return new Empresa($nombre, $u);
            }
        }
        return null;
    }

    public function create(Empresa $e)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "INSERT INTO empresas (Nombre, Fondo, Logo) 
                VALUES (?, ?, ?);";
        $query = self::$conexion->prepare($sql);

        $nombre = $e->getNombre();
        $fondo = $e->getFondo();
        $logo = $e->getLogo();

        if (!$query->bind_param("sss", $nombre, $fondo, $logo)) {
            echo "Fallo la consulta a la base de datos.";
            return false;
        }

        return $query->execute();
    }

    public function update(Empresa $e)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "UPDATE empresas SET Fondo = ?, Logo = ?, ArchivoInicio1 = ?, ArchivoInicio2 = ?, UsuarioPrincipal = ? ";
        $sql .= "WHERE Nombre = ?;";

        $query = self::$conexion->prepare($sql);

        $nombre = $e->getNombre();
        $fondo = $e->getFondo();
        $logo = $e->getLogo();
        $archivo_inicio1 = $e->getArchivoInicio1();
        $archivo_inicio2 = $e->getArchivoInicio2();
        $usuario_principal = $e->getUsuarioPrincipal() ? $e->getUsuarioPrincipal()->getDni() : null;

        if ($usuario_principal) {
            $dni_usuario = $usuario_principal->getDni();
        }
        // Revisar si usuarioPrincipal no da error si es NULL
        if (!$query->bind_param("ssssss", $fondo, $logo, $archivo_inicio1, $archivo_inicio2, $dni_usuario, $nombre)) {
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

        $sql = "DELETE FROM empresas WHERE Nombre = ?;";
        $query = self::$conexion->prepare($sql);

        if (!$query->bind_param("s", $nombre)) {
            echo "Fallo la consulta a la base de datos.";
        }

        return $query->execute();
    }
}
