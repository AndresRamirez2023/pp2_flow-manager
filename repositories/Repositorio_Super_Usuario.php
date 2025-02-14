<?php
require_once __DIR__ . '/../.env.php';
// Incluir clases necesarias
require_once __DIR__ . '/../classes/SuperUsuario.php';
require_once 'Repositorio.php';

class Repositorio_Super_Usuario extends Repositorio
{
    public function login($username, $password)
    {
        $q = "SELECT * FROM super_usuario WHERE username = ?";
        $query = self::$conexion->prepare($q);

        if (!$query) {
            throw new Exception("Error en la preparación de la consulta: " . self::$conexion->error);
        }

        $query->bind_param('s', $username);

        if ($query->execute()) {
            $query->bind_result($username, $password_hash);
            if ($query->fetch()) {
                if (password_verify($password, $password_hash)) {
                    return new SuperUsuario($username);
                }
            }
        }
        return false;
    }
}
