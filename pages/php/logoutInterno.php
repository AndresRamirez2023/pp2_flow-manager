<?php
session_start();
session_destroy();
header('Location: ../internas/sistema/loginInterno.php?mensaje=Se ha cerrado la sesión');