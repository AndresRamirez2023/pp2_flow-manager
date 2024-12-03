<?php
require_once '../repositories/Repositorio_Usuario.php';
require_once '../classes/Usuario.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirigir al login
    header('Location: ../../index.php');
    exit;
}

$repositorio = new Repositorio_Usuario();

// Obtener datos del formulario
$nombreApellido = $_POST['nombreApellido'];
$domicilio = $_POST['Direccion'];
$telefono = $_POST['telefono'];
$CorreoElectronico = $_POST['CorreoElectronico'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$TipoDeUsuario = $_POST['tipoUsuario'];
$clave = isset($_POST['clave']) ? $_POST['clave'] : null; // Clave (puede ser opcional)

// Obtener el usuario actual de la sesión
$usuario = unserialize($_SESSION['usuario']);

// Separar nombre y apellido (asumiendo formato "Nombre Apellido")
$partes = explode(' ', $nombreApellido);
$nombre = $partes[0];
$apellido = isset($partes[1]) ? $partes[1] : '';

// Actualizar datos en el objeto usuario
$usuario->setNombre($nombre);
$usuario->setApellido($apellido);
$usuario->setFechaNac($fechaNacimiento);
$usuario->setDomicilio($domicilio);
$usuario->setTelefono($telefono);
$usuario->setCorreoElectronico($CorreoElectronico);
$usuario->setTipoUsuario($TipoDeUsuario);

// Verificar si se debe actualizar la clave
if (!empty($clave)) {
    $usuario->setClave(password_hash($clave, PASSWORD_DEFAULT)); // Encriptar y actualizar la clave
}

// Actualizar en la base de datos
if ($repositorio->update($usuario)) {
    echo "Datos actualizados correctamente.";
    // Actualizar usuario en la sesión
    $_SESSION['usuario'] = serialize($usuario);
    // Redirigir a la página de perfil
    header('Location: ../pages/internas/perfil.php');
    exit;
} else {
    echo "Error al actualizar los datos.";
}
?>
