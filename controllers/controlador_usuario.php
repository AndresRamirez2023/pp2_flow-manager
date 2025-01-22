<?php
require_once '../classes/Usuario.php'; // Clase Usuario
require_once '../repositories/Repositorio_Usuario.php'; // Repositorio

$dni = trim($_POST['dni']); // Eliminar espacios adicionales
$email = trim($_POST['email']); // Eliminar espacios adicionales

// Validar que el DNI sea exactamente de 8 caracteres y numérico
if (!preg_match('/^\d{8}$/', $dni)) {
    die("DNI no válido. Debe contener exactamente 8 dígitos.");
}


// Instancia del repositorio
$usuarioRepo = new Repositorio_Usuario();

// Continuar con el proceso de creación del usuario si el DNI es válido
$nombreApellido = $_POST['nombreApellido'];
$domicilio = $_POST['domicilio'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$TipoDeUsuario = $_POST['TipoDeUsuario'];
$departamento = $_POST['departamento'];
$clave = $_POST['clave']; // La contraseña

// Separar nombre y apellido
list($nombre, $apellido) = explode(' ', $nombreApellido, 2);

// Crear instancia de Usuario
$departamentoObj = empty($departamento) ? null : new Departamento($departamento);
$usuario = new Usuario($dni, $nombre, $apellido, $fechaNacimiento, $domicilio, $email, $telefono, $TipoDeUsuario, $departamentoObj, $clave);

// Guardar en la base de datos
$usuarioRepo = new Repositorio_Usuario();
$resultado = $usuarioRepo->save($usuario, $clave);

if ($resultado) {
    echo "Usuario agregado correctamente.";
} else {
    echo "Error al agregar el usuario.";
}

?>