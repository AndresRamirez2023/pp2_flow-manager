<?php
require_once __DIR__ . '../../classes/Empresa.php';
require_once 'Repositorio.php';

class Repositorio_Empresa extends Repositorio
{

    public function get_all()
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "SELECT ";
        $sql .= "e.Nombre ";
        $sql .= "FROM Empresas e ";
        $sql .= "INNER JOIN usuarios u ON u.Dni = e.DniPrincipal;";

        $query = self::$conexion->prepare($sql);

        $Nombre = null;
        $DniPrincipal = null;
        $Fondo = null;
        $Logo = null;
        $ArchivoInicio1 = null;
        $ArchivoInicio2 = null;

        if ($query->execute()) {
            $query->bind_result(
                $Nombre,
                $DniPrincipal,
                $Fondo,
                $Logo,
                $ArchivoInicio1,
                $ArchivoInicio2
            );
            $empresas = [];
            while ($query->fetch()) {
                $e = new Empresa($Nombre, $DniPrincipal, $Fondo, $Logo, $ArchivoInicio1, $ArchivoInicio2);
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

        $sql = "SELECT Nombre, DniPrincipal FROM Empresas WHERE Nombre = ? LIMIT 1;";

        $query = self::$conexion->prepare($sql);
        $query->bind_param("s", $nombre);

        $Nombre = null;
        $DniPrincipal = null;

        if ($query->execute()) {
            $query->bind_result($Nombre, $DniPrincipal);
            if ($query->fetch()) {
                return new Empresa($Nombre, $DniPrincipal);
            }
        }
        return null;
    }

    public function create(Empresa $e)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "INSERT INTO empresas (Nombre, Fondo, Logo, ArchivoInicio1, ArchivoInicio2, DniPrincipal) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $query = self::$conexion->prepare($sql);

        $Nombre = $e->getNombre();
        $Fondo = $e->getFondo();
        $Logo = $e->getLogo();
        $ArchivoInicio1 = $e->getArchivoInicio1();
        $ArchivoInicio2 = $e->getArchivoInicio2();
        $DniPrincipal = $e->getDniPrincipal();

        if (!$query->bind_param("ssssss", $Nombre, $Fondo, $Logo, $ArchivoInicio1, $ArchivoInicio2, $DniPrincipal)) {
            echo "Fallo la consulta";
            return false;
        }

        return $query->execute();
    }

    public function update(Empresa $e) {}

    public function delete($nombre) {}
}
