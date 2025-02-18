<?php
require_once '../classes/Usuario.php'; // Clase Usuario
require_once '../repositories/Repositorio_Usuario.php'; // Repositorio
require_once '../classes/Departamento.php'; // Clase Departamento (si aplica)
require_once '../classes/Empresa.php';

class Controlador_Usuario {
    private $usuarioRepo;

    public function __construct() {
        $this->usuarioRepo = new Repositorio_Usuario();
    }

    public function crearUsuario($datos) {
        $dni = trim($datos['dni']);
        $email = trim($datos['email']);

        if (!$this->validarDNI($dni)) {
            die("DNI no válido. Debe contener exactamente 8 dígitos.");
        }

        $nombreApellido = $datos['nombreApellido'];
        $domicilio = $datos['domicilio'];
        $telefono = $datos['telefono'];
        $fechaNacimiento = $datos['fechaNacimiento'];
        $TipoDeUsuario = $datos['TipoDeUsuario'];
        $empresa = $datos['empresa'];
        $departamento = $datos['departamento'];
        $clave = $datos['clave'];

        list($nombre, $apellido) = explode(' ', $nombreApellido, 2);
        $departamentoObj = empty($departamento) ? null : new Departamento($departamento);
        $empresaObj = empty($empresa) ? : new Empresa($empresa);
        $usuario = new Usuario($dni, $nombre, $apellido, $fechaNacimiento, $domicilio, $email, $telefono, $TipoDeUsuario, $empresaObj,  $departamentoObj, $clave);
        
        return $this->usuarioRepo->save($usuario, $clave);
    }

    private function validarDNI($dni) {
        return preg_match('/^\d{8}$/', $dni);
    }
}

// Uso del controlador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new Controlador_Usuario();
    $resultado = $controlador->crearUsuario($_POST);
    
    echo $resultado ? "Usuario agregado correctamente." : "Error al agregar el usuario.";
}
?>
