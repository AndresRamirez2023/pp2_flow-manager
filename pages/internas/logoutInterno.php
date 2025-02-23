<?php
session_start();
session_destroy();
header('Location: loginInterno.php?mensaje=Se ha cerrado la sesión');