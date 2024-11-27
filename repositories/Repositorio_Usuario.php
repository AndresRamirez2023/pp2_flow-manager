<?php
 require_once __DIR__ . '/../classes/config.php';
// Incluir clases necesarias
require_once __DIR__ . '/../classes/Usuario.php';
require_once 'Repositorio.php';

class Repositorio_Usuario extends Repositorio
{
    // Establecer la conexión a la base de datos usando los parámetros de configuración
    public function __construct() {
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
        $q = "SELECT Dni, Nombre, Apellido, FechaNacimiento, Direccion, CorreoElectronico, Telefono, TipoDeUsuario, Departamento, clave FROM usuarios WHERE CorreoElectronico = ?";
        $query = self::$conexion->prepare($q);
        $query->bind_param('s', $CorreoElectronico);
    
        if ($query->execute()) {
            $query->bind_result($Dni, $Nombre, $Apellido, $FechaNacimiento, $Direccion, $CorreoElectronico_db, $Telefono, $TipoDeUsuario, $Departamento, $clave_db);
    
            if ($query->fetch()) {
                // Comparar la clave ingresada con la clave de la base de datos
                if ($clave === $clave_db) {
                    // Crear el objeto Departamento solo si no es NULL
                    $departamento = $Departamento !== null ? new Departamento($Departamento) : null;
    
                    // Retornar un nuevo objeto Usuario
                    return new Usuario($Dni, $Nombre, $Apellido, $FechaNacimiento, $Direccion, $CorreoElectronico_db, $Telefono, $TipoDeUsuario, $departamento, $clave_db);
                }
            }
        }
    
        return null; // Devuelve null si no encuentra al usuario o la clave no coincide
    }
    
    
    public function save(Usuario $usuario, $clave) {
        // Definición de la consulta
        $q = "INSERT INTO usuarios (Dni, Nombre, Apellido, FechaNacimiento, Direccion, CorreoElectronico, Telefono, TipoDeUsuario, Departamento, clave)";
        $q .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // Preparar la consulta
        $query = self::$conexion->prepare($q);
    
        if ($query === false) {
            die('Error en la preparación de la consulta: ' . self::$conexion->error);
        }
    
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
        $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);
    
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
            // Capturar y mostrar el error si falla
            die('Error al ejecutar la consulta: ' . $query->error);
        }
    }
    
    
    
}
