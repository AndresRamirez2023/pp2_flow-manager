<?php
require_once __DIR__ . '/../repositories/Repositorio_solicitud.php';
require_once __DIR__ . '/../repositories/Repositorio_usuario.php'; // Asegúrate de que exista esta clase
require_once __DIR__ . '/../classes/Usuario.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificamos si se está creando una solicitud o actualizando el estado
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        // Acción de crear solicitud
        if ($accion === 'crear') {
            // Datos del formulario para crear solicitud
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

        // Acción de actualizar estado
        if ($accion === 'actualizar') {
            $DniSolicitante = $_POST['DniSolicitante'] ?? null;
            $estado = $_POST['estado'] ?? null;
            $id_licencia = $_POST['id_licencia'] ?? null;

            if ($DniSolicitante && $estado && $id_licencia) {
                if (isset($_SESSION['usuario'])) {
                    $usuario = unserialize($_SESSION['usuario']);
                    $isRRHH = $usuario->esRRHH();

                    if ($isRRHH) {
                        $repositorio = new Repositorio_solicitud();
                        if ($repositorio->updateSolicitud($estado, $DniSolicitante, $id_licencia)) {
                            echo "Estado actualizado correctamente.";
                        } else {
                            echo "Hubo un error al actualizar el estado.";
                        }
                    } else {
                        echo "No tienes permisos para actualizar el estado.";
                    }
                } else {
                    echo "No estás autenticado para realizar esta acción.";
                }
            } else {
                echo "Faltan datos para actualizar el estado.";
            }
        }
    }
}

// Obtener las solicitudes de licencia dependiendo de si el usuario es RRHH o no
if (isset($_SESSION['usuario'])) {
    $usuario = unserialize($_SESSION['usuario']);
    $isRRHH = $usuario->esRRHH(); // Método para verificar si el usuario es RRHH
    $dniSolicitante = $usuario->getDni(); // Obtener el DNI del usuario logueado
} else {
    header("Location: login.php");
    exit;
}

// Dependiendo del tipo de usuario (RRHH o no), se obtienen las solicitudes correspondientes
$repositorio = new Repositorio_solicitud();

if ($isRRHH) {
    // Si es RRHH, obtener todas las solicitudes
    $solicitudes = $repositorio->mostrarSolicitud();
} else {
    // Si no es RRHH, obtener solo las solicitudes del usuario logueado
    $solicitudes = $repositorio->mostrarSolicitud($dniSolicitante);
}
