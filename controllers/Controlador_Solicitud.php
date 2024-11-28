<?php
require_once __DIR__ . '/../repositories/Repositorio_solicitud.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoLicencia = $_POST['tipoLicencia'] ?? null;
    $DniSolicitante = $_POST['DniSolicitante'] ?? null;
    $fechaInicio = $_POST['fechaInicio'] ?? null;
    $fechaFin = $_POST['fechaFin'] ?? null;

    // Validar que los datos no estén vacíos
    if ($tipoLicencia && $DniSolicitante && $fechaInicio && $fechaFin) {
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
        echo "Por favor complete todos los campos.";
    }
}
