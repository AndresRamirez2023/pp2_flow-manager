<?php

require_once __DIR__ . '/../repositories/Repositorio_Departamento.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Departamento.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_departamento = $_POST['nombreDepartamento'] ?? null;
    $dni_director = $_POST['dniDirector'] ?? null;
    $accion = $_POST['accion'] ?? '';

    if ($nombre_departamento && $dni_director) {
        $repositorio = new Repositorio_Departamento();

        // Validar que el usuario (director) exista
        $usuario_existe = $repositorio->getUsuarioPorDni($dni_director);

        if ($usuario_existe) {
            // Crear un objeto Usuario para el director
            $director = new Usuario($dni_director, null, null, null, null, null, null, null, null, null);

            // Crear el departamento y asignarle al director
            $departamento = new Departamento($nombre_departamento, $director);

            // Guardar el departamento en la base de datos
            if ($repositorio->save($departamento)) {
                echo "El departamento '{$nombre_departamento}' fue asignado correctamente al director con DNI {$dni_director}.";
            } else {
                echo "Error al guardar el departamento.";
            }
        } else {
            echo "No existe un usuario con el DNI proporcionado.";
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }

    if ($accion === 'editar'){
        echo "Editar departamento: $nombre_departamento (DNI del Director: $dni_director)";
        // Aquí puedes redirigir o mostrar un formulario de edición

    } elseif ($accion === 'eliminar'){
        //Logica para eliminar el departamento

    }

    }
    

