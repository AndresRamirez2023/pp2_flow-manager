<?php
require_once '../../controllers/Controlador_Sesion.php';

if (empty($_POST['CorreoElectronico']) || empty($_POST['clave'])) {  // Cambié 'usuario' a 'CorreoElectronico' y 'password' a 'clave'
    $redirigir = '/pages/internas/login.php?mensaje=Error: Todos los campos son obligatorios';
} else {
    $cs = new Controlador_Sesion();
    $login = $cs->login($_POST['CorreoElectronico'], $_POST['clave']);  // Cambié 'usuario' a 'CorreoElectronico' y 'password' a 'clave'
    if ($login[0] === true) {
        $redirigir = '/pages/internas/panelPrincipal.php';
    } else {
        $redirigir = '../internas/login.php?mensaje=' . $login[1];
    }
}
header('Location: '.$redirigir);