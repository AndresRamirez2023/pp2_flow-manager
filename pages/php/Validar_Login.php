<?php
require_once '../../controllers/Controlador_Sesion.php';

if (empty($_POST['username']) || empty($_POST['password'])) {
    $redirigir = '/pages/internas/login.php?mensaje=Error: Todos los campos son obligatorios.';
} else {
    $cs = new Controlador_Sesion();
    $login = $cs->login($_POST['username'], $_POST['password'], $_GET['empresa']);
    if ($login[0] === true) {
        $_SESSION['empresa'] = $_GET['empresa'];
        $redirigir = '../internas/panelPrincipal.php';
    } else {
        $redirigir = '../internas/login.php?' . 'empresa=' . $_GET['empresa'] . '&mensaje=' . $login[1] . '&tipo=danger';
    }
}
header('Location: ' . $redirigir);
