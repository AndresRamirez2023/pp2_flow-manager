<?php
require_once '../../controllers/controlador_usuario.php';

session_start();

$usuario = unserialize($_SESSION['usuario']);

if (empty($_GET['dni'])) {
    $_SESSION['mensaje'] = "Error al intentar eliminar el usuario, el DNI está vacío.";
    $_SESSION['mensaje_tipo'] = "danger";
    header("Location: ../internas/gestion.php");
    die();
} elseif ($_GET['dni'] === $usuario->getDni()) {
    $_SESSION['mensaje'] = "Error al intentar eliminar el usuario, no puede borrar el usuario de la sesión actual.";
    $_SESSION['mensaje_tipo'] = "danger";
    header("Location: ../internas/gestion.php");
    die();
}

$cu = new Controlador_Usuario();

$result = $cu->delete($_GET['dni']);

if ($result) {
    $_SESSION['mensaje'] = "El usuario <b>" . $_GET['dni'] . "</b> se ha eliminado del sistema correctamente.";
    $_SESSION['mensaje_tipo'] = "info";
    $redirigir = "../internas/gestion.php";
} else {
    $_SESSION['mensaje'] = "Error al intentar eliminar el usuario.";
    $_SESSION['mensaje_tipo'] = "danger";
    $redirigir = "../internas/gestion.php";
}

header("Location: $redirigir");
