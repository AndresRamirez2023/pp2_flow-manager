<?php
require_once '../repositories/Repositorio_Usuario.php';
require_once '../classes/Usuario.php';

session_start();

class Controlador_Actualizacion {
    private $repositorio;
    
    public function __construct() {
        $this->repositorio = new Repositorio_Usuario();
    }
    
    public function verificarAutenticacion() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ../../index.php');
            exit;
        }
    }
    
    public function actualizarUsuario($datos) {
        // Obtener usuario actual de la sesión
        $usuario = unserialize($_SESSION['usuario']);
        
        // Separar nombre y apellido
        $partes = explode(' ', trim($datos['nombreApellido']), 2);
        $nombre = $partes[0];
        $apellido = isset($partes[1]) ? $partes[1] : '';

        // Actualizar datos del usuario
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setFechaNac($datos['fechaNacimiento']);
        $usuario->setDomicilio($datos['Direccion']);
        $usuario->setTelefono($datos['telefono']);
        $usuario->setCorreoElectronico($datos['CorreoElectronico']);
        $usuario->setTipoUsuario($datos['tipoUsuario']);
        
        // Actualizar clave si se proporciona
        if (!empty($datos['clave'])) {
            $usuario->setClave(password_hash($datos['clave'], PASSWORD_DEFAULT));
        }
        
        return $this->repositorio->update($usuario);
    }
    
    public function procesarActualizacion() {
        $this->verificarAutenticacion();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->actualizarUsuario($_POST)) {
                $_SESSION['usuario'] = serialize($this->repositorio->findById($_POST['id'])); // Obtener datos actualizados
                header('Location: ../pages/internas/perfil.php');
                exit;
            } else {
                echo "Error al actualizar los datos.";
            }
        }
    }
}

// Instancia y ejecución
$controlador = new Controlador_Actualizacion();
$controlador->procesarActualizacion();
?>
