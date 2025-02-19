<?php
require_once '../repositories/Repositorio_Usuario.php';
require_once '../classes/Usuario.php';

session_start();

class Controlador_Actualizacion {
    private $repositorio;

    public function __construct() {
        $this->repositorio = new Repositorio_Usuario();
    }

    public function actualizarUsuario() {
        if (!$this->usuarioEnSesion()) {
            header('Location: ../../index.php');
            exit;
        }

        $usuario = unserialize($_SESSION['usuario']);
        $this->actualizarDatosUsuario($usuario);

        if ($this->repositorio->update($usuario)) {
            $_SESSION['usuario'] = serialize($usuario);
            header('Location: ../pages/internas/perfil.php');
            exit;
        } else {
            echo "Error al actualizar los datos.";
        }
    }

    private function usuarioEnSesion() {
        return isset($_SESSION['usuario']);
    }

    private function actualizarDatosUsuario($usuario) {
        $usuario->setNombreApellido($_POST['nombreApellido']);
        $usuario->setFechaNac($_POST['fechaNacimiento']);
        $usuario->setDomicilio($_POST['Direccion']);
        $usuario->setTelefono($_POST['telefono']);
        $usuario->setCorreoElectronico($_POST['CorreoElectronico']);
        $usuario->setTipoUsuario($_POST['tipoUsuario']);

        if (!empty($_POST['clave'])) {
            $usuario->setClave(password_hash($_POST['clave'], PASSWORD_DEFAULT));
        }
    }
}

$controlador = new Controlador_Actualizacion();
$controlador->actualizarUsuario();
