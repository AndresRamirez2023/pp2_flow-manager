<?php
session_start();
$empresa = $_SESSION['empresa'];
session_destroy();
header('Location: ../internas/login.php?empresa=' . $empresa . '&mensaje=Se ha cerrado la sesión.&tipo=warning');
