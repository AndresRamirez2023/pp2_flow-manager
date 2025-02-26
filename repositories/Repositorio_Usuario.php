<?php
require_once __DIR__ . '/../.env.php';
// Incluir clases necesarias
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Departamento.php';
require_once 'Repositorio.php';

class Repositorio_Usuario extends Repositorio
{

    protected $selectSql = "SELECT u.Dni, u.NombreApellido, u.FechaNacimiento, u.Domicilio, u.CorreoElectronico, 
                        u.Telefono, u.TipoDeUsuario, d.Nombre, d.DirectorACargo, d.Empresa
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
        $nombre_apellido = null;
        $fecha_nac = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_usuario = null;
        $nombre_departamento = null;
        $nombre_empresa = null;

        if ($query->execute()) {
            $query->bind_result(
                $dni,
                $nombre_apellido,
                $fecha_nac,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_usuario,
                $nombre_departamento,
                $nombre_empresa
            );

            $usuarios = [];
            while ($query->fetch()) {
                $e = new Empresa($nombre_empresa);
                $departamento = new Departamento($nombre_departamento, null, $e);

                $e = new Usuario(
                    $dni,
                    $nombre_apellido,
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

    public function login($username, $clave, $empresa)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $q = "SELECT * FROM usuarios u LEFT JOIN Departamentos d ON d.Nombre = u.Departamento ";

        $dni = null;
        $nombre_apellido = null;
        $fecha_nacimiento = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_de_usuario = null;
        $departamento = null;
        $clave_encriptada = null;
        $nombre_departamento = null;
        $nombre_empresa = null;
        $director_a_cargo = null;
        $type = is_numeric($username);

        if ($type === 'double') {
            $q .= "WHERE Dni = ?;";
        } elseif ($type === 'string') {
            $q .= "WHERE CorreoElectronico = ?;";
        }

        $query = self::$conexion->prepare($q);
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        if ($type === 'double') {
            $query->bind_param('i', $username);
        } elseif ($type === 'string') {
            $query->bind_param('s', $username);
        }

        if ($query->execute()) {
            $query->bind_result(
                $dni,
                $nombre_apellido,
                $fecha_nacimiento,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_de_usuario,
                $departamento,
                $clave_encriptada,
                $nombre_departamento,
                $nombre_empresa,
                $director_a_cargo
            );
            if ($query->fetch()) {
                // TODO: Comentado para pruebas, descomentar para versión final
                // if ($nombre_empresa !== null && $empresa == $nombre_empresa) {
                    if (password_verify($clave, $clave_encriptada ?: '')) {
                        $e = new Empresa($nombre_empresa);
                        $departamento = new Departamento($nombre_departamento, null, $e);

                        return new Usuario(
                            $dni,
                            $nombre_apellido,
                            $fecha_nacimiento,
                            $domicilio,
                            $correo_electronico,
                            $telefono,
                            $tipo_de_usuario,
                            $departamento
                        );
                    }
                // }
            }
        }
        return null;
    }

    public function save(Usuario $usuario, $clave)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        // Obtener los datos del objeto $usuario
        $dni = $usuario->getDni();
        $nombre_apellido = $usuario->getNombreApellido();
        $fecha_nacimiento = $usuario->getfechaNac();
        $domicilio = $usuario->getDomicilio();
        $correo_electronico = $usuario->getCorreoElectronico();
        $telefono = $usuario->getTelefono();
        $tipo_de_usuario = $usuario->getTipoUsuario();
        $departamento = $usuario->getDepartamento();

        // Encriptar la contraseña
        $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

        // **INSERCIÓN EN LA BASE DE DATOS**

        // Definición de la consulta
        $q = "INSERT INTO usuarios (Dni, NombreApellido, FechaNacimiento, Domicilio, CorreoElectronico, Telefono, TipoDeUsuario, Departamento, clave)";
        $q .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $query = self::$conexion->prepare($q);
        if ($query === false) {
            die('Error en la preparación de la consulta: ' . self::$conexion->error);
        }

        // Vincular los parámetros
        $query->bind_param(
            "issssssss",
            $dni,
            $nombre_apellido,
            $fecha_nacimiento,
            $domicilio,
            $correo_electronico,
            $telefono,
            $tipo_de_usuario,
            $departamento,
            $clave_encriptada
        );

        return $query->execute();
    }

    public function get_by_dni($dni)
    {
        if (!self::$conexion) {
            throw new Exception("La conexión no ha sido inicializada.");
        }

        $nombre_apellido = null;
        $fecha_nac = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_usuario = null;
        $nombre_departamento = null;
        $director_a_cargo = null;
        $nombre_empresa = null;

        $newSql = $this->selectSql . " WHERE u.Dni = ? LIMIT 1;";

        $query = self::$conexion->prepare($newSql);
        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param("i", $dni);
        $query->execute();
        $query->bind_result(
            $dni,
            $nombre_apellido,
            $fecha_nac,
            $domicilio,
            $correo_electronico,
            $telefono,
            $tipo_usuario,
            $nombre_departamento,
            $director_a_cargo,
            $nombre_empresa
        );

        if ($query->fetch()) {
            $e = new Empresa($nombre_empresa);
            $departamento = new Departamento($nombre_departamento, $director_a_cargo, $e);
            $query->close();
            return new Usuario(
                $dni,
                $nombre_apellido,
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
        $nombre_apellido = null;
        $fecha_nac = null;
        $domicilio = null;
        $correo_electronico = null;
        $telefono = null;
        $tipo_usuario = null;
        $nombre_departamento = null;
        $director_a_cargo = null;
        $nombre_empresa = null;

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
            $nombre_apellido,
            $fecha_nac,
            $domicilio,
            $correo_electronico,
            $telefono,
            $tipo_usuario,
            $nombre_departamento,
            $director_a_cargo,
            $nombre_empresa
        );

        if ($query->fetch()) {
            $e = new Empresa($nombre_empresa);
            $departamento = new Departamento($nombre_departamento, $director_a_cargo, $e);
            $query->close();
            return new Usuario(
                $dni,
                $nombre_apellido,
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
        $nombre_apellido = $usuario->getNombreApellido();
        $fecha_nacimiento = $usuario->getFechaNac();
        $domicilio = $usuario->getDomicilio();
        $correo_electronico = $usuario->getCorreoElectronico();
        $telefono = $usuario->getTelefono();
        $tipo_de_usuario = $usuario->getTipoUsuario();

        if (!empty($clave_encriptada)) {
            $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

            // Vincular parámetros con la contraseña
            $query->bind_param(
                "sssssssi",
                $nombre_apellido,
                $fecha_nacimiento,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_de_usuario,
                $clave,
                $dni
            );
        } else {
            // Vincular parámetros sin la contraseña
            $query->bind_param(
                "ssssssi",
                $nombre_apellido,
                $fecha_nacimiento,
                $domicilio,
                $correo_electronico,
                $telefono,
                $tipo_de_usuario,
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
