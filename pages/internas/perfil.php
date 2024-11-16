<?php
session_start();
$usuario = null;
if (isset($_SESSION['usuario'])) {
    $usuario = unserialize($_SESSION['usuario']);
} else {
    // TODO: Redirige al login si no está iniciada la sesión
    // header('Location: ../../index.php');
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil &#65381; Flow Manager</title>

    <!-- Bootstrap 5 CSS -->
    <link
      href="../../bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Bootstrap Icons CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />

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
              alt="Logo Empresa"
            />
          </a>

          <!-- Navegación -->
          <div class="navbar-nav ms-auto">
            <a class="btn btn-custom nav-link" href="panelPrincipal.php"
              >Panel Principal</a
            >
            <a class="btn btn-custom nav-link" href="mensajes.php"
              >Mensajes y Archivos</a
            >
            <a class="btn btn-custom nav-link" href="departamento.php"
              >Departamento</a
            >
            <a class="btn btn-custom nav-link" href="licencias.php"
              >Licencias y Vacaciones</a
            >
            <!-- Foto de perfil y menú flotante -->
            <div class="profile-container position-relative">
              <div class="nav-profile me-4" id="profileMenu" role="button">
                <img
                  src="../img/empleador.jpg"
                  alt="Foto de perfil"
                  class="profile-pic-img"
                />
              </div>

              <!-- Menú desplegable -->
              <div class="dropdown-menu" id="profileDropdown">
                <a href="perfil.php" class="dropdown-item">
                  <i class="bi bi-person-fill"></i> Perfil</a
                >
                <a href="login.php" class="dropdown-item">
                  <i class="bi bi-box-arrow-right"></i> Cerrar sesión</a
                >
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
            <form id="form-agregar-usuario" class="row g-3">
              <div class="col-md-6">
                <label for="nombreApellido" class="form-label"
                  >Nombre y Apellido</label
                >
                <input
                  type="text"
                  class="form-control"
                  id="nombreApellido"
                  required
                  value="Juan Doe"
                />
              </div>
              <div class="col-md-6">
                <label for="dni" class="form-label">DNI</label>
                <input
                  type="text"
                  class="form-control"
                  id="dni"
                  disabled
                  value="4233412"
                />
              </div>
              <div class="col-md-6">
                <label for="domicilio" class="form-label">Domicilio</label>
                <input
                  type="text"
                  class="form-control"
                  id="domicilio"
                  value="254 Avenida Principal, Rosario"
                />
              </div>
              <div class="col-md-6">
                <label for="telefono" class="form-label"
                  >Número de Teléfono</label
                >
                <input
                  type="tel"
                  class="form-control"
                  id="telefono"
                  required
                  value="3415596846"
                />
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  disabled
                  value="ab@gmail.com"
                />
              </div>
              <div class="col-md-6">
                <label for="fechaNacimiento" class="form-label"
                  >Fecha de Nacimiento</label
                >
                <input
                  type="date"
                  class="form-control"
                  id="fechaNacimiento"
                  required
                />
              </div>
              <div class="col-md-6">
                <label for="tipoUsuario" class="form-label"
                  >Tipo de usuario</label
                >
                <select
                  name="tipoUsuario"
                  id="tipoUsuario"
                  class="form-select"
                  disabled
                >
                  <option value="">Empleado</option>
                  <option value="">RRHH</option>
                  <option value="">Directivo</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label"
                  >Cambiar Contraseña</label
                >
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  required
                />
              </div>
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary w-100">
                  Guardar Datos
                </button>
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
