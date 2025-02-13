<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &#65381; Flow Manager</title>
    <link rel="icon" href="../img/Icon - FlowManager.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link
    href="../../assets/dist/css/bootstrap.min.css"
    rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/panel.css">
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container img {
            width: 120px;
            height: auto;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1rem;
        }

        .forgot-password {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="../img/Icon - FlowManager.png" alt="Flow Manager Logo">
        </div>
        <form action="../php/Validar_Login.php" method="POST">
            <h2><b>FLOW MANAGER</b></h2>
            <p class="text-center text-muted">Ingreso al sistema <b>Interno</b></p>

            <!-- Username -->
            <div class="mb-3">
                <label for="CorreoElectronico" class="form-label">Usuario</label>
                <input type="text" id="CorreoElectronico" name="CorreoElectronico" class="form-control"
                    placeholder="Ingrese el mail del usuario" required>
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

        <?php
        if (isset($_GET['mensaje'])) {
            echo '<div id="mensaje" class="alert alert-primary text-center mt-3">
                    <p>' . $_GET['mensaje'] . '</p></div>';
        }
        ?>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>