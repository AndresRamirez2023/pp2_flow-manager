<?php
require_once '../classes/Usuario.php'; // Clase Usuario
require_once '../repositories/Repositorio_Usuario.php'; // Repositorio

// Recoger datos del formulario
$dni = $_POST['dni'];
$nombreApellido = $_POST['nombreApellido'];
$domicilio = $_POST['domicilio'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$tipoUsuario = $_POST['tipoUsuario'];
$departamento = $_POST['departamento'];
$clave = $_POST['clave'];  // La contraseña


// Separar nombre y apellido
list($nombre, $apellido) = explode(' ', $nombreApellido, 2);

// Crear instancia de Usuario
$departamentoObj = empty($departamento) ? null : new Departamento($departamento);
$usuario = new Usuario($dni, $nombre, $apellido, $fechaNacimiento, $domicilio, $email, $telefono, $tipoUsuario, $departamentoObj, $clave);


// Guardar en la base de datos
$usuarioRepo = new Repositorio_Usuario();
$resultado = $usuarioRepo->save($usuario, $clave);  // Ahora pasas ambos argumentos

if ($resultado) {
    echo "Usuario agregado correctamente.";
} else {
    echo "Error al agregar el usuario.";
}

?>