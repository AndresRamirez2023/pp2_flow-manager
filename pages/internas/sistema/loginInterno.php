<?php
session_start();

if (isset($_SESSION['super_user'])) {
    header('Location: nuevaEmpresa.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &#65381; Flow Manager</title>
    <link rel="icon" href="../../img/Icon - FlowManager.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link
        href="../../../assets/dist/css/bootstrap.min.css"
        rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/internas.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/loginInterno.css">
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="../../img/Icon - FlowManager.png" alt="Flow Manager Logo">
        </div>
        <form action="../../php/Validar_Login_Interno.php" method="POST">
            <h2><b>FLOW MANAGER</b></h2>
            <p class="text-center text-muted">Ingreso al sistema <b>Interno</b></p>

            <!-- Mensaje de backend -->
            <?php
            if (isset($_GET['mensaje'])) {
                echo '<div id="mensaje" class="alert alert-warning text-center mt-3">
                    <p>' . $_GET['mensaje'] . '</p></div>';
            }
            ?>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" id="username" name="username" class="form-control"
                    placeholder="Ingrese el nombre de usuario" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Ingrese la contraseña" required>
            </div>

            <!-- Forgot Password -->
            <div class="mb-3 text-center">
                <a href="#" class="forgot-password">&#191;Olvidaste tu contraseña?</a>
            </div>

            <!-- Login Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
        </form>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../../../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>