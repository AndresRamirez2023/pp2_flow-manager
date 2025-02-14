<?php
session_start();
session_destroy();
header('Location: loginInterno.php?mensaje=se ha cerrado la sesión');