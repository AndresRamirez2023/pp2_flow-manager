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
    <title>Mensajes &#65381; Flow Manager</title>

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
      <div class="row full-view">
        <!-- Menú lateral (solo para la página de mensajes) -->
        <nav class="col-md-2 sidebar d-md-block">
          <a href="#"><i class="bi bi-envelope"></i> Mensajes Recibidos</a>
          <a href="#"><i class="bi bi-bell"></i> Avisos</a>
          <a href="#"
            ><i class="bi bi-check2-square"></i> Documentación firmada</a
          >
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-10 main-content">
          <div class="row messages full-view">
            <!-- Columna izquierda: Lista de mensajes -->
            <div class="col-md-4 message-list">
              <h2>Mensajes</h2>
              <div class="list-group">
                <a
                  href="#"
                  class="list-group-item list-group-item-action"
                  data-type="aviso"
                  data-title="Fechas de Evaluaciones de Desempeño"
                  data-date="13-10-2024 09:21"
                >
                  <div class="w-100 justify-content-between message-text">
                    <h5 class="mb-1">Fechas de Evaluaciones asdasdasdasda</h5>
                    <small>2024-10-13</small>
                  </div>
                  <p class="mb-1">Aviso</p>
                </a>
                <a
                  href="#"
                  class="list-group-item list-group-item-action"
                  data-type="documento"
                  data-title="Documento pendiente"
                  data-date="2024-10-12"
                  data-file="Recibo-Octubre.pdf"
                >
                  <div class="w-100 justify-content-between message-text">
                    <h5 class="mb-1">Recibo de Sueldo Octubre</h5>
                    <small>2024-10-12</small>
                  </div>
                  <p class="mb-1">Documento</p>
                </a>
                <!-- Agrega más mensajes según sea necesario -->
              </div>
            </div>

            <!-- Columna derecha: Detalle del mensaje seleccionado -->
            <div class="col-md-8 message-detail">
              <div class="inner-message-detail">
                <h2 id="message-title">Selecciona un mensaje</h2>
                <p id="message-type"></p>
                <small id="message-date"></small>
                <div id="message-content">
                  <!-- Aquí se mostrará el contenido del mensaje -->
                </div>
                <!-- Vista previa del archivo (PDF, imagen, etc.) -->
                <iframe
                  id="file-preview"
                  class="d-none"
                  width="100%"
                  height="400px"
                ></iframe>

                <!-- Botón para firmar, solo si es un Documento -->
                <button id="sign-button" class="btn btn-custom mt-3 d-none">
                  Firmar
                </button>
              </div>
            </div>
          </div>
        </main>

        <!-- Modal para firmar el documento -->
        <div
          class="modal fade"
          id="signModal"
          tabindex="-1"
          aria-labelledby="signModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="signModalLabel">
                  Firmar documento
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Cerrar"
                ></button>
              </div>
              <div class="modal-body">
                <form>
                  <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input
                      type="password"
                      class="form-control"
                      id="password"
                      placeholder="Ingrese su contraseña"
                    />
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary-custom"
                  data-bs-dismiss="modal"
                >
                  Cancelar
                </button>
                <button type="button" class="btn btn-custom">Confirmar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/profile-menu.js"></script>
    <script src="../js/mensajes.js"></script>
  </body>
</html>
