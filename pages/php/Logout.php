<?php
require_once '../../classes/Empresa.php';

session_start();
$empresa = unserialize($_SESSION['empresa']);
session_destroy();
header('Location: ../internas/login.php?empresa=' . $empresa->getNombre() . '&mensaje=Se ha cerrado la sesión.&tipo=warning');
