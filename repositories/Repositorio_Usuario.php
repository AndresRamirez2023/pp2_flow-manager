<?php
require_once __DIR__ . '/../.env.php';
// Incluir clases necesarias
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Departamento.php';
require_once 'Repositorio.php';

class Repositorio_Usuario extends Repositorio
{

    protected $selectSql = "SELECT u.Dni, u.Nombre, u.Apellido, u.FechaNacimiento, u.Domicilio, u.CorreoElectronico, 
                        u.Telefono, u.TipoDeUsuario, d.Nombre
                        FROM Usuarios u
                        LEFT JOIN Departamentos d ON d.Nombre = u.Departamento";

    public function __construct()
    {
        parent::__construct();
    }

    // Revisar Departamento y Empresa
    public function get_all()
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $query = self::$conexion->prepare($this->selectSql);

        $dni = null;
        $nombre = null;
        $apellido = null;
        $fecha_nac = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_usuario = null;
        $nombre_departamento = null;

        if ($query->execute()) {
            $query->bind_result(
                $dni,
                $nombre,
                $apellido,
                $fecha_nac,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_usuario,
                $nombre_departamento
            );

            $usuarios = [];
            while ($query->fetch()) {
                $departamento = new Departamento($nombre_departamento);

                $e = new Usuario(
                    $dni,
                    $nombre,
                    $apellido,
                    $fecha_nac,
                    $domicilio,
                    $correo_electronico,
                    $telefono,
                    $tipo_usuario,
                    $departamento
                );
                $usuarios[] = $e;
            }
            return $usuarios;
        }
    }

    public function login($correo_electronico, $clave)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }
    
        $q = "SELECT * FROM usuarios WHERE CorreoElectronico = ?";
        $query = self::$conexion->prepare($q);
    
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param('s', $correo_electronico);

        $query->execute();
        $result = $query->get_result();
        if ($row = $result->fetch_assoc()) {
            // Verificar la contraseña
            if (password_verify($clave, $row['Clave'])) {
                // Verificar el Departamento
                $departamentoObj = null;
                if ($row['Departamento'] !== 'Sin Departamento' && $row['Departamento'] !== null) {
                    $departamentoObj = new Departamento($row['Departamento']);
                }
    
                // Verificar la Empresa (no puede ser NULL ni 'Sin Empresa')
                $empresaObj = null;
                if ($row['Empresa'] !== 'Sin Empresa' && $row['Empresa'] !== null) {
                    $empresaObj = new Empresa($row['Empresa']);
                } else {
                    // Si la empresa es NULL o 'Sin Empresa', puedes lanzar una excepción o manejarlo de otra manera
                    throw new Exception("El usuario no tiene una empresa válida.");
                }
    
                // Retornar un objeto Usuario con los datos obtenidos
                return new Usuario(
                    $row['Dni'],
                    $row['Nombre'],
                    $row['Apellido'],
                    $row['FechaNac'],
                    $row['Domicilio'],
                    $row['CorreoElectronico'],
                    $row['Telefono'],
                    $row['TipoUsuario'],
                    $departamentoObj  // Pasa el objeto Departamento o null
                );
            }
        }

        $query->close();
        return false; // Retorna false si el usuario no existe o la contraseña es incorrecta
    }
    
    public function save(Usuario $usuario, $clave)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        // Obtener los datos del objeto $usuario
        $dni = $usuario->getDni();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $fecha_nacimiento = $usuario->getfechaNac();
        $domicilio = $usuario->getDomicilio();
        $correo_electronico = $usuario->getCorreoElectronico();
        $telefono = $usuario->getTelefono();
        $tipoDeUsuario = $usuario->getTipoUsuario();
        $Departamento = $usuario->getDepartamento();
        $empresa = 'flowmanager';

        // Encriptar la contraseña
        $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

        // **INSERCIÓN EN LA BASE DE DATOS**

        // Definición de la consulta
        $q = "INSERT INTO usuarios (Dni, Nombre, Apellido, FechaNacimiento, Domicilio, CorreoElectronico, Telefono, TipoDeUsuario, Departamento, clave)";
        $q .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $query = self::$conexion->prepare($q);
        if ($query === false) {
            die('Error en la preparación de la consulta: ' . self::$conexion->error);
        }

        // Vincular los parámetros
        $query->bind_param(
            "isssssssss",
            $dni,
            $nombre,
            $apellido,
            $fecha_nacimiento,
            $domicilio,
            $correo_electronico,
            $telefono,
            $tipoDeUsuario,
            $Departamento,
            $clave_encriptada
        );

        return $query->execute();
    }

    public function get_by_dni($dni)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $dni = null;
        $nombre = null;
        $apellido = null;
        $fecha_nac = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_usuario = null;
        $nombre_departamento = null;

        $newSql = $this->selectSql . " WHERE u.Dni = ? LIMIT 1;";

        $query = self::$conexion->prepare($newSql);
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param("i", $dni);
        $query->execute();
        $query->bind_result(
            $dni,
            $nombre,
            $apellido,
            $fecha_nac,
            $domicilio,
            $correo_electronico,
            $telefono,
            $tipo_usuario,
            $nombre_departamento
        );

        if ($query->fetch()) {
            $departamento = new Departamento($nombre_departamento);
            $query->close();
            return new Usuario(
                $dni,
                $nombre,
                $apellido,
                $fecha_nac,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_usuario,
                $departamento
            );
        }
        $query->close();
        return null;
    }

    /**
     * Verificar si el correo electrónico ya existe
     */
    public function get_by_email($correo_electronico)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $dni = null;
        $nombre = null;
        $apellido = null;
        $fecha_nac = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_usuario = null;
        $nombre_departamento = null;

        $newSql = $this->selectSql . " WHERE u.CorreoElectronico = ? LIMIT 1;";

        $query = self::$conexion->prepare($newSql);
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param("i", $correo_electronico);

        $query->execute();

        // Vincular los resultados a variables
        $query->bind_result(
            $dni,
            $nombre,
            $apellido,
            $fecha_nac,
            $domicilio,
            $correo_electronico,
            $telefono,
            $tipo_usuario,
            $nombre_departamento
        );

        if ($query->fetch()) {
            $departamento = new Departamento($nombre_departamento);
            $query->close();
            return new Usuario(
                $dni,
                $nombre,
                $apellido,
                $fecha_nac,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_usuario,
                $departamento
            );
        }
        $query->close();
        return null;
    }


    public function update(Usuario $usuario, $clave = null)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        // Definir la consulta base
        $q = "UPDATE usuarios 
              SET Nombre = ?, 
                  Apellido = ?, 
                  FechaNacimiento = ?, 
                  Domicilio = ?, 
                  CorreoElectronico = ?, 
                  Telefono = ?, 
                  TipoDeUsuario = ?";

        if (!empty($nuevaPassword)) {
            $q .= ", clave = ?";
        }

        $q .= " WHERE Dni = ?";

        // Preparar la consulta
        $query = self::$conexion->prepare($q);

        if ($query === false) {
            die('Error en la preparación de la consulta: ' . self::$conexion->error);
        }

        // Obtener los datos del objeto $usuario
        $dni = $usuario->getDni();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $fecha_nacimiento = $usuario->getFechaNac();
        $domicilio = $usuario->getDomicilio();
        $correo_electronico = $usuario->getCorreoElectronico();
        $telefono = $usuario->getTelefono();
        $tipoDeUsuario = $usuario->getTipoUsuario();

        if (!empty($clave_encriptada)) {
            $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

            // Vincular parámetros con la contraseña
            $query->bind_param(
                "sssssssss", // Tipos
                $nombre,
                $apellido,
                $fecha_nacimiento,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipoDeUsuario,
                $clave,
                $dni
            );
        } else {
            // Vincular parámetros sin la contraseña
            $query->bind_param(
                "ssssssss", // Tipos
                $nombre,
                $apellido,
                $fecha_nacimiento,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipoDeUsuario,
                $dni
            );
        }

        // Ejecutar la consulta
        if ($query->execute()) {
            $query->close();
            return true;
        } else {
            die('Error al ejecutar la consulta: ' . $query->error);
        }
    }

    public function obtenerTipoDeUsuario($dni)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $tipo_usuario = null;

        $q = "SELECT TipoDeUsuario FROM usuarios WHERE Dni = ?";
        $query = self::$conexion->prepare($q);

        if ($query === false) {
            die('Error al preparar la consulta: ' . self::$conexion->error);
        }

        $query->bind_param("s", $dni);

        if ($query->execute()) {
            $query->bind_result($tipo_usuario);
            if ($query->fetch()) {
                $query->close();
                return $tipo_usuario;
            }
        }

        return null; // Retornar null si no se encuentra el usuario
    }

    public function delete($dni)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $sql = "DELETE FROM usuarios WHERE dni = ?;";
        $query = self::$conexion->prepare($sql);

        if (!$query->bind_param("i", $dni)) {
            echo "fallo la consulta";
        }

        return $query->execute();
    }
}
