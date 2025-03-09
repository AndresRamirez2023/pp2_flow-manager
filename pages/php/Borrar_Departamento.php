<?php
require_once '../../controllers/Controlador_Departamento.php';

session_start();

$usuario = unserialize($_SESSION['usuario']);

if (empty($_GET['nombre'])) {
    $_SESSION['mensaje'] = "Error al intentar eliminar el departamento, el Nombre está vacío.";
    $_SESSION['mensaje_tipo'] = "danger";
    header("Location: ../internas/gestion.php#departamentos");
    die();
}

$cd = new Controlador_Departamento();

$result = $cd->delete($_GET['nombre']);

if ($result) {
    $nombre_departamento = strstr($_GET['nombre'], '_') ? substr(strstr($_GET['nombre'], '_'), 1) : $_GET['nombre'];
    $_SESSION['mensaje'] = "El departamento <b>" . $nombre_departamento . "</b> se ha eliminado del sistema correctamente.";
    $_SESSION['mensaje_tipo'] = "info";
    $redirigir = "../internas/gestion.php#departamentos";
} else {
    $_SESSION['mensaje'] = "Error al intentar eliminar el departamento.";
    $_SESSION['mensaje_tipo'] = "danger";
    $redirigir = "../internas/gestion.php#departamentos";
}

header("Location: $redirigir");
