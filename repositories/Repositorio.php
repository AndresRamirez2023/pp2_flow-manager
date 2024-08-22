<?php
require_once '.env.php';

abstract class Repositorio
{
    protected static $conexion = null;

    public function __construct()
    {
        $credenciales = credenciales();
        if (is_null(self::$conexion)) {
            $credenciales = credenciales();
            self::$conexion = new mysqli(
                $credenciales['servidor'],
                $credenciales['usuario'],
                $credenciales['clave'],
                $credenciales['base_de_datos']
            );
            if (self::$conexion->connect_error) {
                $error = 'Error al conectar:' . self::$conexion->connect_error;
                self::$conexxion = null;
                die($error);
            }
            self::$conexion->set_charset('utf8mb4');
        }
    }
}