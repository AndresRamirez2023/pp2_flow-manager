<?php
require_once '../../controllers/Controlador_Usuario.php';
session_start();

header('Content-Type: application/json');

$response = [];

$usuario = unserialize($_SESSION['usuario']);
$empresa = $usuario->getDepartamento()->getEmpresa();

if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
    $cu = new Controlador_Usuario();

    try {
        $usuarios = $cu->get_all($q, $empresa);

        if (!empty($usuarios)) {
            $response = array_map(function ($usuario) {
                return [
                    'dni' => $usuario->getDni(),
                    'nombre' => $usuario->getNombreApellido(),
                    'email' => $usuario->getCorreoElectronico(),
                ];
            }, $usuarios);
        }
    } catch (Exception $e) {
        $response = [];
    }
}

echo json_encode($response);
exit;
