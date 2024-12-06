<?php
require_once __DIR__ . '/../classes/config.php';
// Incluir clases necesarias
require_once __DIR__ . '/../classes/Usuario.php';
require_once 'Repositorio.php';

class Repositorio_Usuario extends Repositorio
{
    // Establecer la conexión a la base de datos usando los parámetros de configuración
    public function __construct()
    {
        // Obtener las credenciales de la función
        $credenciales = credenciales();

        // Usar los valores de las credenciales
        $host = $credenciales['servidor'];
        $user = $credenciales['usuario'];
        $pass = $credenciales['clave'];
        $dbname = $credenciales['base_de_datos'];

        // Si no hay conexión abierta, crear una nueva
        if (self::$conexion === null) {
            self::$conexion = new mysqli($host, $user, $pass, $dbname);
            if (self::$conexion->connect_error) {
                die("Conexión fallida: " . self::$conexion->connect_error);
            }
        }
    }

    public function login($CorreoElectronico, $clave)
    {
        $q = "SELECT * FROM usuarios WHERE CorreoElectronico = ?";
        $query = self::$conexion->prepare($q);
        $query->bind_param('s', $CorreoElectronico);

        if ($query->execute()) {
            $query->bind_result($Dni, $nombre, $apellido, $CorreoElectronico, $fechaNac, $domicilio, $telefono, $tipoUsuario, $departamento, $clave_encriptada);

            if ($query->fetch()) {
                if (password_verify($clave, $clave_encriptada)) {
                    // Crear un objeto Departamento si el valor no es "Sin Departamento" o null
                    $departamentoObj = null;
                    if ($departamento !== 'Sin Departamento' && $departamento !== null) {
                        $departamentoObj = new Departamento($departamento); // Crear el objeto Departamento si es válido
                    }

                    // Crear y devolver el objeto Usuario
                    return new Usuario(
                        $Dni,
                        $nombre,
                        $apellido,
                        $fechaNac,
                        $domicilio,
                        $CorreoElectronico,
                        $telefono,
                        $tipoUsuario,
                        $departamentoObj,  // Pasar el objeto Departamento o null
                        $clave
                    );
                }
            }
        }
        return false;
    }
    public function save(Usuario $usuario, $clave)
    {
        // Obtener los datos del objeto $usuario
        $Dni = $usuario->getDni();
        $Nombre = $usuario->getNombre();
        $Apellido = $usuario->getApellido();
        $FechaNacimiento = $usuario->getfechaNac();
        $Direccion = $usuario->getDomicilio();
        $CorreoElectronico = $usuario->getCorreoElectronico();
        $Telefono = $usuario->getTelefono();
        $TipoDeUsuario = $usuario->getTipoUsuario();
        $Departamento = $usuario->getDepartamento();

        // **VALIDACIONES BACKEND**

        // Validar DNI (exactamente 8 dígitos)
        if (!preg_match('/^\d{8}$/', $Dni)) {
            die('Error: El DNI debe contener exactamente 8 dígitos numéricos.');
        }

        // Validar nombre y apellido (solo letras y espacios, entre 2 y 50 caracteres)
        if (!preg_match('/^[A-Za-zÀ-ÿ\s]{2,50}$/', $Nombre) || !preg_match('/^[A-Za-zÀ-ÿ\s]{2,50}$/', $Apellido)) {
            die('Error: El nombre y el apellido solo pueden contener letras y espacios, entre 2 y 50 caracteres.');
        }

        // Validar fecha de nacimiento (formato válido y usuario mayor de edad)
        $fechaActual = new DateTime();
        $fechaNacimiento = DateTime::createFromFormat('Y-m-d', $FechaNacimiento);
        if (!$fechaNacimiento || $fechaNacimiento > $fechaActual->modify('-18 years')) {
            die('Error: El usuario debe ser mayor de 18 años.');
        }

        // Validar dirección (mínimo 5 caracteres)
        if (strlen($Direccion) < 5 || strlen($Direccion) > 100) {
            die('Error: La dirección debe contener entre 5 y 100 caracteres.');
        }

        // Validar correo electrónico
        if (!filter_var($CorreoElectronico, FILTER_VALIDATE_EMAIL)) {
            die('Error: El correo electrónico no tiene un formato válido.');
        }

        // Validar teléfono (7-15 dígitos opcionales con "+")
        if (!preg_match('/^\+?\d{7,15}$/', $Telefono)) {
            die('Error: El número de teléfono debe tener entre 7 y 15 dígitos.');
        }

        // Validar tipo de usuario
        $tiposValidos = ['RRHH', 'Directivo', 'Empleado'];
        if (!in_array($TipoDeUsuario, $tiposValidos)) {
            die('Error: Tipo de usuario inválido.');
        }

        // Validar contraseña (mínimo 8 caracteres, al menos una letra y un número)
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/', $clave)) {
            die('Error: La contraseña debe tener al menos 8 caracteres, incluyendo una letra y un número.');
        }

        // Encriptar la contraseña
        $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

        // **INSERCIÓN EN LA BASE DE DATOS**

        // Definición de la consulta
        $q = "INSERT INTO usuarios (Dni, Nombre, Apellido, FechaNacimiento, Direccion, CorreoElectronico, Telefono, TipoDeUsuario, Departamento, clave)";
        $q .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $query = self::$conexion->prepare($q);
        if ($query === false) {
            die('Error en la preparación de la consulta: ' . self::$conexion->error);
        }

        // Vincular los parámetros
        $query->bind_param(
            "ssssssssss",
            $Dni,
            $Nombre,
            $Apellido,
            $FechaNacimiento,
            $Direccion,
            $CorreoElectronico,
            $Telefono,
            $TipoDeUsuario,
            $Departamento,
            $clave_encriptada
        );

        // Ejecutar la consulta
        if ($query->execute()) {
            return true; // Retorna true si se ejecutó correctamente
        } else {
            die('Error al ejecutar la consulta: ' . $query->error);
        }
    }


    public function update(Usuario $usuario, $clave = null)
    {
        // Definir la consulta base
        $q = "UPDATE usuarios 
              SET Nombre = ?, 
                  Apellido = ?, 
                  FechaNacimiento = ?, 
                  Direccion = ?, 
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
        $Dni = $usuario->getDni();
        $Nombre = $usuario->getNombre();
        $Apellido = $usuario->getApellido();
        $FechaNacimiento = $usuario->getFechaNac();
        $Direccion = $usuario->getDomicilio();
        $CorreoElectronico = $usuario->getCorreoElectronico();
        $Telefono = $usuario->getTelefono();
        $TipoDeUsuario = $usuario->getTipoUsuario();

        if (!empty($clave_encriptada)) {
            $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

            // Vincular parámetros con la contraseña
            $query->bind_param(
                "sssssssss", // Tipos
                $Nombre,
                $Apellido,
                $FechaNacimiento,
                $Direccion,
                $CorreoElectronico,
                $Telefono,
                $TipoDeUsuario,
                $clave,
                $Dni
            );
        } else {
            // Vincular parámetros sin la contraseña
            $query->bind_param(
                "ssssssss", // Tipos
                $Nombre,
                $Apellido,
                $FechaNacimiento,
                $Direccion,
                $CorreoElectronico,
                $Telefono,
                $TipoDeUsuario,
                $Dni
            );
        }

        // Ejecutar la consulta
        if ($query->execute()) {
            return true;
        } else {
            die('Error al ejecutar la consulta: ' . $query->error);
        }
    }

    public function obtenerTipoDeUsuario($Dni)
{
    $q = "SELECT TipoDeUsuario FROM usuarios WHERE Dni = ?";
    $query = self::$conexion->prepare($q);

    if ($query === false) {
        die('Error al preparar la consulta: ' . self::$conexion->error);
    }

    $query->bind_param("s", $Dni);

    if ($query->execute()) {
        $query->bind_result($tipoUsuario);
        if ($query->fetch()) {
            return $tipoUsuario;
        }
    }

    return null; // Retornar null si no se encuentra el usuario
}


}
