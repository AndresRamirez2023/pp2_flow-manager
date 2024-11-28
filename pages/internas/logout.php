<?php
session_start();
session_destroy();
header('Location: login.php?mensaje=se ha cerrado la sesion');