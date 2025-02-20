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
        $sql .= "e.Nombre, e.DniUsuarioPrincipal, e.Fondo, e.Logo, e.ArchivoInicio1, e.ArchivoInicio2 ";
        $sql .= "FROM Empresas e ";
        $sql .= "INNER JOIN usuarios u ON u.Dni = e.UsuarioPrincipal;";

        $query = self::$conexion->prepare($sql);

        $nombre = null;
        $dni_usuario_principal = null;
        $fondo = null;
        $logo = null;
        $archivo_inicio1 = null;
        $archivo_inicio2 = null;
        $ru = new Repositorio_Usuario();

        if ($query->execute()) {
            $query->bind_result(
                $nombre,
                $dni_usuario_principal,
                $fondo,
                $logo,
                $archivo_inicio1,
                $archivo_inicio2
            );

            $usuario_principal = $ru->get_by_dni($dni_usuario_principal);
            $empresas = [];
            while ($query->fetch()) {
                $e = new Empresa($nombre, $usuario_principal, $fondo, $logo, $archivo_inicio1, $archivo_inicio2);
                $empresas[] = $e;
            }
            return $empresas;
        }
    }

    public function get_by_name($nombre)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT Nombre, UsuarioPrincipal FROM Empresas WHERE Nombre = ? LIMIT 1;";

        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $nombre);

        $usuario_principal = null;

        if ($query->execute()) {
            $query->bind_result($nombre, $usuario_principal);
            if ($query->fetch()) {
                return new Empresa($nombre, $usuario_principal);
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

        // Revisar si usuarioPrincipal no da error si es NULL
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
        $usuario_principal = $e->getUsuarioPrincipal();

        if ($usuario_principal) {
            $dni_usuario = $usuario_principal->getDni();
        }
        // Revisar si usuarioPrincipal no da error si es NULL
        if (!$query->bind_param("ssssss", $nombre, $fondo, $logo, $archivo_inicio1, $archivo_inicio2, $dni_usuario)) {
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
