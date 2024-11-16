<?php
session_start();

if (isset($_SESSION['usuario'])) {
    // Redirige al panel principal si el usuario ya inició sesión
    header("Location: pages\internas\panelPrincipal.php");
    exit();
} else {
    // Redirige a la página de login si no ha iniciado sesión
    header("Location: pages\internas\login.php");
    exit();
}
?>
