<?php
session_start();
session_destroy();
header('Location: index.php?mensaje=Se ha cerrado la sesión');