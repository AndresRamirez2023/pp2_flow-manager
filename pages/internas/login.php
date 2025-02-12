<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login &#65381; Flow Manager</title>
    <link rel="icon" href="../img/Icon - FlowManager.png">

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css?family=Poppins&display=swap"
      rel="stylesheet"
    />

    <!-- Bootstrap 5 CSS -->
    <link
      href="../../bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/panel.css" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link href="../css/login.css" rel="stylesheet" />
  </head>
  <body>
    <div class="full-container">
      <div class="login-container">
        <div class="form-container">
          <form class="login-form" action="../php/Validar_Login.php" method="POST">
            <h2 class="text-center mb-4">Nombre empresa<br />Ingreso</h2>
            <p class="text-center text-muted">
              Bienvenido al gestor de empleados<br /><b>Flow Manager</b>
            </p>
            <?php
            if (isset($_GET['mensaje'])) {
                echo '<div id="mensaje" class="alert alert-danger text-center">
                    <p>' . $_GET['mensaje'] . '</p></div>';
            }
            ?>
            <!-- Username -->
            <div class="mb-3">
              <label for="CorreoElectronico" class="form-label">Usuario</label>
              <input
                type="text"
                id="CorreoElectronico"
                name="CorreoElectronico"
                class="form-control"
                placeholder="Ingrese el mail del usuario"
                required
              />
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                placeholder="Ingrese la contraseña"
                required
              />
            </div>

            <!-- Forgot Password -->
            <div class="mb-3 text-center">
              <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
            </div>

            <!-- Login Button -->
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
          </form>
        </div>
        <div class="right-background"></div>
      </div>

    <!-- Bootstrap 5 JS -->
    <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
