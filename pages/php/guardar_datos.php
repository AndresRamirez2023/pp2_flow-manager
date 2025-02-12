<?php
session_start();

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'flow_manager';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'crearEmpresa') {
        $nombreEmpresa = $_POST['nombreEmpresa'];
        $logoEmpresa = $_FILES['logoEmpresa'];
        $fondoEmpresa = $_FILES['fondoEmpresa'];

        // Validar y mover archivos subidos
        $directorioSubida = '../uploads/';
        $logoPath = $directorioSubida . basename($logoEmpresa['name']);
        $fondoPath = $directorioSubida . basename($fondoEmpresa['name']);

        if (!move_uploaded_file($logoEmpresa['tmp_name'], $logoPath)) {
            $_SESSION['mensaje'] = "Error al subir el logo de la empresa.";
            header('Location: crear_empresa.php');
            exit;
        }

        if ($fondoEmpresa['name'] && !move_uploaded_file($fondoEmpresa['tmp_name'], $fondoPath)) {
            $_SESSION['mensaje'] = "Error al subir el fondo de la empresa.";
            header('Location: crear_empresa.php');
            exit;
        }

        // Insertar datos en la base de datos
        $sql = "INSERT INTO empresas (nombre, logo, fondo) VALUES (:nombre, :logo, :fondo)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                ':nombre' => $nombreEmpresa,
                ':logo' => $logoPath,
                ':fondo' => $fondoPath
            ]);
            $_SESSION['mensaje'] = "Empresa creada exitosamente.";
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al crear la empresa: " . $e->getMessage();
        }

        header('Location: crear_empresa.php');
        exit;
    }

    if ($accion === 'crearUsuario') {
        $nombreApellido = $_POST['nombreApellido'];
        $dni = $_POST['dni'];
        $domicilio = $_POST['domicilio'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $fechaNacimiento = $_POST['fechaNacimiento'];
        $tipoDeUsuario = $_POST['TipoDeUsuario'];
        $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

        // Insertar datos del usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre_apellido, dni, domicilio, telefono, email, fecha_nacimiento, tipo_usuario, clave) 
                VALUES (:nombreApellido, :dni, :domicilio, :telefono, :email, :fechaNacimiento, :tipoDeUsuario, :clave)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                ':nombreApellido' => $nombreApellido,
                ':dni' => $dni,
                ':domicilio' => $domicilio,
                ':telefono' => $telefono,
                ':email' => $email,
                ':fechaNacimiento' => $fechaNacimiento,
                ':tipoDeUsuario' => $tipoDeUsuario,
                ':clave' => $clave
            ]);
            $_SESSION['mensaje'] = "Usuario creado exitosamente.";
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al crear el usuario: " . $e->getMessage();
        }

        header('Location: crear_empresa.php');
        exit;
    }
}
?>
