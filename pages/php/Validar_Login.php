<?php
require_once '../../controllers/Controlador_Sesion.php';

session_start();

if (empty($_POST['username']) || empty($_POST['password'])) {
    $redirigir = '/pages/internas/login.php?mensaje=Error: Todos los campos son obligatorios.';
} else {
    $cs = new Controlador_Sesion();
    $empresa = unserialize($_SESSION['empresa']);
    $login = $cs->login($_POST['username'], $_POST['password'], $empresa->getNombre());
    if ($login[0] === true) {
        $redirigir = '../internas/panelPrincipal.php';
    } else {
        $redirigir = '../internas/login.php?' . 'empresa=' . $empresa->getNombre() . '&mensaje=' . $login[1] . '&tipo=danger';
    }
}
header('Location: ' . $redirigir);
