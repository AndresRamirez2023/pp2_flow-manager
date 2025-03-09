<?php
require_once '../../controllers/Controlador_Sesion.php';
require_once '../../controllers/Controlador_Empresa.php';

session_start();

if (empty($_POST['username']) || empty($_POST['password'])) {
    $redirigir = '/pages/internas/login.php?mensaje=Error: Todos los campos son obligatorios.';
} else {
    $cs = new Controlador_Sesion();
    $ce = new Controlador_Empresa();
    $empresa = null;
    if (isset($_GET['empresa'])) {
        $empresa = $ce->get_by_name($_GET['empresa']);
        $_SESSION['empresa'] = serialize($empresa);

        $empresa = unserialize($_SESSION['empresa']);
    }
    $login = $cs->login($_POST['username'], $_POST['password'], is_null($empresa) ?: $empresa->getNombre());
    if ($login[0] === true) {
        $redirigir = '../internas/panelPrincipal.php';
    } else {
        $redirigir = '../internas/login.php?' . (is_null($empresa) === null ? '' : 'empresa=' . $empresa->getNombre() . '&') . 'mensaje=' . $login[1] . '&tipo=danger';
    }
}
header('Location: ' . $redirigir);
