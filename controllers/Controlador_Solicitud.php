<?php
require_once __DIR__ . '/../repositories/Repositorio_solicitud.php';
require_once __DIR__ . '/../repositories/Repositorio_usuario.php'; // Asegúrate de que exista esta clase
require_once __DIR__ . '/../classes/Usuario.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoLicencia = $_POST['tipoLicencia'] ?? null;
    $DniSolicitante = $_POST['DniSolicitante'] ?? null;
    $fechaInicio = $_POST['fechaInicio'] ?? null;
    $fechaFin = $_POST['fechaFin'] ?? null;

    // Validar que los datos no estén vacíos
    if ($tipoLicencia && $DniSolicitante && $fechaInicio && $fechaFin) {
        // Verificar el rol del solicitante
        $repositorioUsuario = new Repositorio_Usuario();
        $tipoUsuario = $repositorioUsuario->obtenerTipoDeUsuario($DniSolicitante);

        if ($tipoUsuario === 'Empleado') {
            // Validar que las fechas sean coherentes
            if ($fechaInicio <= $fechaFin) {
                $repositorio = new Repositorio_solicitud();

                // Guardar la solicitud de licencia
                if ($repositorio->guardarSolicitud($tipoLicencia, $DniSolicitante, $fechaInicio, $fechaFin)) {
                    echo "La solicitud de licencia fue registrada exitosamente.";
                } else {
                    echo "Hubo un error al registrar la solicitud.";
                }
            } else {
                echo "La fecha de inicio debe ser anterior o igual a la fecha de fin.";
            }
        } else {
            echo "No tienes permisos para solicitar una licencia.";
        }
    } else {
        echo "Por favor complete todos los campos.";
    }
}