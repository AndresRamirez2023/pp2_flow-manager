<?php
require_once '../../repositories/Repositorio_Usuario.php';
require_once '../../classes/Usuario.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

// Deserializar el usuario guardado en la sesión
$usuario = unserialize($_SESSION['usuario']);

// Verificar si el usuario es de tipo RRHH
$isRRHH = $usuario->esRRHH(); // Guardamos el valor si es RRHH
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil &#65381; Flow Manager</title>
  <link rel="icon" href="../img/Icon - FlowManager.png">

  <!-- Bootstrap 5 CSS -->
  <link
    href="../../bootstrap.min.css"
    rel="stylesheet" />

  <!-- Bootstrap Icons CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    rel="stylesheet" />

  <!-- Hoja de estilo personalizada -->
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/panel.css" />
</head>

<body>
  <!-- Header -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid">
        <!-- Logo de la empresa cliente -->
        <a class="navbar-brand" href="panelPrincipal.php">
          <img
            id="logo"
            src="../img/Logo - FlowManager.svg"
            alt="Logo Empresa" />
        </a>

        <!-- Navegación -->
        <div class="navbar-nav ms-auto">
          <a class="btn btn-custom nav-link" href="panelPrincipal.php">Panel Principal</a>
          <a class="btn btn-custom nav-link" href="mensajes.php">Mensajes y Archivos</a>
          <a class="btn btn-custom nav-link" href="departamento.php">Departamento</a>
          <a class="btn btn-custom nav-link" href="licencias.php">Licencias y Vacaciones</a>
          <!-- Foto de perfil y menú flotante -->
          <div class="profile-container position-relative">
            <div class="nav-profile me-4" id="profileMenu" role="button">
              <img
                src="../img/empleador.jpg"
                alt="Foto de perfil"
                class="profile-pic-img" />
            </div>

            <!-- Menú desplegable -->
            <div class="dropdown-menu" id="profileDropdown">
              <a href="perfil.php" class="dropdown-item">
                <i class="bi bi-person-fill"></i> Perfil</a>
              <a href="login.php" class="dropdown-item">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <div class="container-fluid">
    <div class="row full-view">
      <!-- Contenido principal -->
      <main class="col-md-12 main-content">
        <!-- Sección para agregar usuarios -->
        <div class="container my-4 bg-white rounded shadow-sm">
          <h2>Modificar datos</h2>
          <form id="form-modificar-usuario" class="row g-3" method="post" action="../../controllers/Controlador_actualizacion.php">
            <div class="col-md-6">
              <label for="nombreApellido" class="form-label">Nombre y Apellido</label>
              <input type="text" class="form-control" id="nombreApellido" name="nombreApellido" required value="Juan Doe" />
            </div>
            <div class="col-md-6">
              <label for="dni" class="form-label">DNI</label>
              <input type="text" class="form-control" id="dni" name="dni" disabled value="42178619" />
            </div>
            <div class="col-md-6">
              <label for="Direccion" class="form-label">Domicilio</label>
              <input type="text" class="form-control" id="Direccion" name="Direccion" value="254 Avenida Principal, Rosario" />
            </div>
            <div class="col-md-6">
              <label for="telefono" class="form-label">Número de Teléfono</label>
              <input type="tel" class="form-control" id="telefono" name="telefono" required value="3415596846" />
            </div>
            <div class="col-md-6">
              <label for="CorreoElectronico" class="form-label">Email</label>
              <input type="email" class="form-control" id="CorreoElectronico" name="CorreoElectronico" />
            </div>
            <div class="col-md-6">
              <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
              <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required />
            </div>
            
              <?php if ($isRRHH): ?>
                <div class="col-md-6">
                <label for="tipoUsuario" class="form-label">Tipo de usuario</label>
                <select name="tipoUsuario" id="tipoUsuario" class="form-select">
                  <option value="Empleado">Empleado</option>
                  <option value="RRHH">RRHH</option>
                  <option value="Directivo">Directivo</option>
                </select>
            </div><!-- Columna solo para RRHH -->
          <?php endif; ?>

          <div class="col-md-6">
            <label for="nueva_clave" class="form-label">Cambiar Contraseña</label>
            <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" />
          </div>
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary w-100">Guardar Datos</button>
          </div>
          </form>

        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile-menu.js"></script>
</body>

</html>