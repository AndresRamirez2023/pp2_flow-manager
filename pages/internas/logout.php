<?php
session_start();
session_destroy();
header('Location: index.php?mensaje=se ha cerrado la sesion');