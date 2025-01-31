<?php


require_once __DIR__ . '/../repositories/Repositorio_Departamento.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Departamento.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre_departamento = $_POST['nombreDepartamento'] ?? null;
    $dni_director = $_POST['dniDirector'] ?? null;
    $accion = $_POST['accion'] ?? '';

    $repositorio = new Repositorio_Departamento();

    if ($accion === 'agregar') {
        if ($nombre_departamento && $dni_director) {
            // Validar que el usuario (director) exista
            $usuario_existe = $repositorio->getUsuarioPorDni($dni_director);

            if ($usuario_existe) {
                $director = new Usuario($dni_director, null, null, null, null, null, null, null, null, null);
                $departamento = new Departamento($nombre_departamento, $director);

                if ($repositorio->save($departamento)) {
                    header("Location: ../pages/internas/gestion.php?mensaje=agregado");
                } else {
                    echo "Error al guardar el departamento.";
                }
            } else {
                echo "No existe un usuario con el DNI proporcionado.";
            }
        } else {
            echo "Por favor, complete todos los campos.";
        }
    } elseif ($accion === 'editar') {

        $nombreNuevo = trim($_POST['nombreDepartamento'] ?? null);
        $dniViejo = trim($_POST['dniDirector'] ?? null);
        $dniNuevo = trim($_POST['nuevoDniDirector'] ?? null);

        if ($nombreNuevo && $dniViejo) {
            // Si el DNI nuevo no está vacío, se cambia; de lo contrario, se mantiene el DNI actual
            $dniFinal = !empty($dniNuevo) ? $dniNuevo : $dniViejo;

            // Verificar si el nuevo DNI corresponde a un usuario existente
            $usuario_existe = $repositorio->getUsuarioPorDni($dniFinal);

            // Verificar si el DNI ya está en uso (excepto el DNI viejo, si no ha cambiado)
            if ($dniNuevo !== '' && $dniNuevo !== $dniViejo) {
                $dni_en_uso = $repositorio->verificarDniEnUso($dniNuevo); // Agregar función en el repositorio para verificar el DNI
                if ($dni_en_uso) {
                    // Si el DNI está en uso, mostrar mensaje de error y no actualizar
                    echo "El DNI proporcionado ya está en uso por otro director.";
                    exit;
                }
            }

            if ($usuario_existe) {
                // Actualizar el departamento con el nuevo DNI (o el mismo si no cambió)
                $resultado = $repositorio->actualizarDepartamento($nombreNuevo, $dniViejo, $dniFinal);

                if ($resultado) {
                    header("Location: ../pages/internas/gestion.php?mensaje=editado");
                    exit;
                } else {
                    header("Location: ../views/departamentos.php?mensaje=error");
                    exit;
                }
            } else {
                echo "No existe un usuario con el DNI proporcionado.";
            }
        } else {
            echo "Por favor, complete todos los campos correctamente.";
        }
    } elseif ($accion === 'eliminar') {
        if ($nombre_departamento) {
            $resultado = $repositorio->EliminarDepartamento($nombre_departamento);

            header("Content-Type: application/json");
            if ($resultado) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo eliminar el departamento"]);
            }
            exit();
        }
    }
}
