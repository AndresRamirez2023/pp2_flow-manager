<?php
require_once '../../classes/ControladorSesion.php';
session_start();

// Verificar si la sesión 'usuario' está definida
if (!isset($_SESSION['usuario'])) {
  // Redirigir con un mensaje de error
  header('Location: login.php?mensaje=Error: Debes iniciar sesión');
  exit();
}

// Verificar si la sesión 'usuario' tiene una cadena serializada válida
if (!is_string($_SESSION['usuario']) || !@unserialize($_SESSION['usuario'])) {
  // Si no es válida, redirigir a login con mensaje de error
  header('Location: login.php?mensaje=Error: Sesión inválida');
  exit();
}

// Deserializar y usar los datos del usuario
$usuario = unserialize($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel Principal &#65381; Flow Manager</title>
    <link rel="icon" href="../img/Icon - FlowManager.png">
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Roboto&display=swap"
      rel="stylesheet"
    />
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

    <!-- FullCalendar CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css"
      rel="stylesheet"
    />
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

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
                  <i class="bi bi-person-fill"></i> Perfil
                </a>
                <a href="logout.php" class="dropdown-item">
                  <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row full-view">
        <!-- Contenido principal -->
        <main class="main-content">
          <div class="container">
            <h1>Avisos</h1>

            <!-- Documentos informativos -->
            <div class="row full-view">
              <div class="col-md-6">
                <iframe
                  src="DS_PP2_Proyecto_Gestión_de_Empleados.docx.pdf"
                  width="90%"
                  height="100%"
                ></iframe>
              </div>
              <div class="col-md-6">
                <iframe
                  src="DS_PP2_Proyecto_Gestión_de_Empleados_Final.docx.pdf"
                  width="90%"
                  height="100%"
                ></iframe>
              </div>
            </div>

            <!-- Calendario -->
            <h1>Calendario Laboral</h1>

            <div class="justify-content-center">
              <div id="calendar" class="mb-4 w-50"></div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/profile-menu.js"></script>
    <script src="../js/calendar.js"></script>
  </body>
</html>
