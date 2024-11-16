<?php
session_start();
$usuario = null;
if (isset($_SESSION['usuario'])) {
    $usuario = unserialize($_SESSION['usuario']);
} else {
    // TODO: Redirige al login si no está iniciada la sesión
    // header('Location: ../../index.php');
}
// TODO: Agregar funcionalidades necesarias
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Departamento &#65381; Flow Manager</title>
    <link rel="icon" href="../img/Icon - FlowManager.png">

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
                <a href="login.php" class="dropdown-item">
                  <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">
        <!-- Contenido principal -->
        <main class="main-content">
          <div class="container">
            <!-- Documentos informativos -->
            <div class="row">
              <div class="col-md-6">
                <h1>Integrantes</h1>
                <h5 class="mt-5 mb-3">Director: Juán Reyes</h5>
                <ul class="row">
                  <div class="col-6">
                    <li class="mt-3">Juan Pérez</li>
                    <li class="mt-3">María González</li>
                    <li class="mt-3">Carlos Rodríguez</li>
                    <li class="mt-3">Ana Fernández</li>
                    <li class="mt-3">Lucía Gómez</li>
                    <li class="mt-3">Santiago López</li>
                    <li class="mt-3">Valeria Martínez</li>
                    <li class="mt-3">Federico Díaz</li>
                    <li class="mt-3">Sofía Sosa</li>
                    <li class="mt-3">Alejandro Torres</li>
                  </div>
                  <div class="col-6">
                    <li class="mt-3">Martina Romero</li>
                    <li class="mt-3">Gonzalo Sánchez</li>
                    <li class="mt-3">Carolina Benítez</li>
                    <li class="mt-3">Agustín Silva</li>
                    <li class="mt-3">Julieta Álvarez</li>
                    <li class="mt-3">Manuel Méndez</li>
                    <li class="mt-3">Camila Herrera</li>
                    <li class="mt-3">Nicolás Castro</li>
                    <li class="mt-3">Florencia Ramírez</li>
                    <li class="mt-3">Diego Ruiz</li>
                  </div>
                </ul>
              </div>
              <div class="col-md-6">
                <!-- Calendario -->
                <h1>Próximos eventos</h1>

                <div class="justify-content-center">
                  <div id="calendar" class="mb-4 w-100"></div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/profile-menu.js"></script>
    <script src="../js/calendar2.js"></script>
  </body>
</html>
