<?php

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/Departamento.php';
require_once __DIR__ . '/../classes/Usuario.php';

class Repositorio_Departamento extends Repositorio
{
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
