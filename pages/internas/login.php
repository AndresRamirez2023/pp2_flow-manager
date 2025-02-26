<?php
require_once '../../controllers/Controlador_Empresa.php';
session_start();

if (isset($_SESSION['usuario'])) {
  header('Location: panelPrincipal.php');
}
if (isset($_GET['mensaje']) && isset($_GET['tipo'])) {
  $_SESSION['mensaje'] = $_GET['mensaje'];
  $_SESSION['mensaje_tipo'] = $_GET['tipo'];
}

$nombre_empresa = null;
if (isset($_GET['empresa'])) {
  $buscar_nombre_empresa = trim($_GET['empresa']);

  $ce = new Controlador_Empresa();
  $e = $ce->get_by_name($buscar_nombre_empresa);

  if ($e) {
    $nombre_empresa = $e->getNombre();
    $nombre_empresa_limpio = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombre_empresa);

    function encontrarImagen($directorio, $nombre_base)
    {
      $extensiones_posibles = ['png', 'jpg', 'jpeg', 'svg', 'gif'];
      foreach ($extensiones_posibles as $ext) {
        $ruta = "$directorio/$nombre_base.$ext";
        if (file_exists($ruta)) {
          return $ruta;
        }
      }
      return null;
    }

    $path_logo_empresa = encontrarImagen("../../uploads/$nombre_empresa_limpio/images", "logo");
    $path_fondo_empresa = encontrarImagen("../../uploads/$nombre_empresa_limpio/images", "fondo");
  }
}

?>
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
    rel="stylesheet" />

  <!-- Bootstrap 5 CSS -->
  <link
    href="../../assets/dist/css/bootstrap.min.css"
    rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/panel.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link href="../css/login.css" rel="stylesheet" />
</head>

<body>
  <div class="full-container">
    <div class="login-container">
      <div class="form-container">
        <form class="login-form" action="../php/Validar_Login.php?empresa=<?php echo $nombre_empresa ?>" method="POST">
          <div class="text-center">
            <img src="<?php echo isset($path_logo_empresa) ? $path_logo_empresa : '../img/Icon - FlowManager.png'; ?>" alt="Logo de la Empresa" class="logo-empresa">
          </div>
          <h2 class="text-center mb-4"><b><?php echo $nombre_empresa ?: 'Nombre empresa'; ?></b><br />Ingreso</h2>
          <p class="text-center text-muted">
            Bienvenido al gestor de empleados<br /><b>Flow Manager</b>
          </p>
          <?php if (isset($_SESSION['mensaje'])): ?>
            <div id="mensaje" class="text-center alert alert-<?php echo $_SESSION['mensaje_tipo']; ?> mt-3">
              <?php echo $_SESSION['mensaje']; ?>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
            ?>
          <?php endif; ?>
          <!-- Username -->
          <div class="mb-3">
            <label for="username" class="form-label">Correo electronico o DNI</label>
            <input
              id="username"
              name="username"
              class="form-control"
              placeholder="Ingrese el mail o DNI del usuario"
              required />
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
              required />
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
      <div class="right-background"
        style="background-image: url('<?php echo isset($path_fondo_empresa) ? $path_fondo_empresa : '../img/fondo-default.jpg'; ?>');">
      </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>