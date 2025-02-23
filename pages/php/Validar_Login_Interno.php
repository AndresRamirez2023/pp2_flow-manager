<?php
require_once '../../controllers/Controlador_Sesion.php';

if (empty($_POST['username']) || empty($_POST['password'])) {
    $redirigir = '/pages/internas/login.php?mensaje=Error: Todos los campos son obligatorios';
} else {
    $cs = new Controlador_Sesion();
    $login = $cs->loginInterno($_POST['username'], $_POST['password']);
    if ($login[0] === true) {
        $redirigir = '../internas/empresas.php';
    } else {
        $redirigir = '../internas/loginInterno.php?mensaje=' . $login[1];
    }
}
header('Location: '.$redirigir);