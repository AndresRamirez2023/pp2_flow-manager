<?php
require_once '../../classes/Usuario.php';
require_once __DIR__ . '/../../controllers/Controlador_Mensaje.php';

session_start();


// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

// Deserializar el usuario guardado en la sesión
$usuario = unserialize($_SESSION['usuario']);

// Verificar si el usuario es de tipo RRHH
$isRRHH = $usuario->esRRHH(); // Guardamos el valor si es RRHH
$isEmpleado = $usuario->esEmpleado(); // Guardamos el valor si es RRHH



// Obtener el DNI del usuario logueado
$DniReceptor = $usuario->getDni();

$repositorio = new Repositorio_Mensaje();

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
    rel="stylesheet" />
  <!-- Bootstrap 5 CSS -->
  <link
    href="../../assets/dist/css/bootstrap.min.css"
    rel="stylesheet" />

  <!-- Bootstrap Icons CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    rel="stylesheet" />

  <!-- FullCalendar CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css"
    rel="stylesheet" />
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
            alt="Logo Empresa" />
        </a>

        <!-- Navegación -->
        <div class="navbar-nav ms-auto">
          <a class="btn btn-custom nav-link" href="panelPrincipal.php">Panel Principal</a>
          <a class="btn btn-custom nav-link" href="mensajes.php">Mensajes y Archivos</a>
          <a class="btn btn-custom nav-link" href="departamento.php">Departamento</a>
          <a class="btn btn-custom nav-link" href="licencias.php">Licencias y Vacaciones</a>

          <?php if ($isRRHH): // Solo mostrar el botón si es RRHH 
          ?>
            <a class="btn btn-custom nav-link" href="gestion.php">Gestión</a>
          <?php endif; ?>
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
                <i class="bi bi-person-fill"></i> Perfil
              </a>
              <a href="../php/Logout.php" class="dropdown-item">
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
        <a href="#" data-bs-toggle="modal" data-bs-target="#redactarMensajeModal">
          <i class="bi bi-envelope"></i> Redactar mensaje
        </a>

        <a href="#"><i class="bi bi-envelope"></i> Mensajes Recibidos</a>
        <a href="#"><i class="bi bi-bell"></i> Avisos</a>
        <a href="#"><i class="bi bi-check2-square"></i> Documentación firmada</a>
      </nav>

      <!-- Contenido principal -->
      <main class="col-md-10 main-content">
        <div class="row messages full-view">
          <!-- Columna izquierda: Lista de mensajes -->
          <div class="col-md-4 message-list">
            <h2>Mensajes</h2>
            <div class="list-group">
              <a
                <?php
                try {
                  $mensajes = $repositorio->GetAllMensajes($DniReceptor);

                  if (!$mensajes) {
                    echo "<p>No se encontraron mensajes para tu DNI.</p>";
                  } else {
                    foreach ($mensajes as $mensaje) {
                ?>
                <a href="#"
                class="list-group-item list-group-item-action"
                data-type="aviso"
                data-title="<?php echo htmlspecialchars($mensaje['TituloMensaje']); ?>"
                data-date="<?php echo htmlspecialchars($mensaje['FechaHoraMensaje']); ?>">
                <div class="w-100 justify-content-between message-text">
                  <h5 class="mb-1"><?php echo htmlspecialchars($mensaje['TituloMensaje']); ?></h5>
                  <small><?php echo htmlspecialchars($mensaje['FechaHoraMensaje']); ?></small>
                </div>
                <p class="mb-1"><?php echo htmlspecialchars($mensaje['TipoMensaje']); ?></p>
              </a>
        <?php
                    }
                  }
                } catch (Exception $e) {
                  echo "<p>Ocurrió un error al obtener los mensajes: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
        ?>
        </a>
        <a
          href="#"
          class="list-group-item list-group-item-action"
          data-type="documento"
          data-title="Documento pendiente"
          data-date="2024-10-12"
          data-file="Recibo-Octubre.pdf">
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
                height="400px"></iframe>

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
        aria-hidden="true">
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
                aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <form>
                <div class="mb-3">
                  <label for="password" class="form-label">Contraseña</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    placeholder="Ingrese su contraseña" />
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary-custom"
                data-bs-dismiss="modal">
                Cancelar
              </button>
              <button type="button" class="btn btn-custom">Confirmar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para redactar un mensaje -->
  <!-- Modal Redactar Mensaje -->
  <div class="modal fade" id="redactarMensajeModal" tabindex="-1" aria-labelledby="redactarMensajeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="redactarMensajeModalLabel">Redactar Mensaje</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formRedactarMensaje" action="../../controllers/Controlador_Mensaje.php" method="POST" enctype="multipart/form-data">

            <!-- Campo oculto con el DNI del usuario logueado -->
            <input type="hidden" name="DniSolicitante" value="<?php echo $usuario->getDni(); ?>" />
            <div class="mb-3">
              <label for="tituloMensaje" class="form-label">Título del Mensaje</label>
              <input type="text" class="form-control" id="tituloMensaje" name="tituloMensaje" placeholder="Ingrese el título" required>
            </div>

            <div class="mb-3">
              <label for="tipoMensaje" class="form-label">Tipo de Mensaje</label>
              <select id="tipoMensaje" name="tipoMensaje" class="form-control" required>
                <option value="Aviso">Aviso</option>
                <option value="Documentacion">Documentación</option>
              </select>
            </div>

            <div class="mb-3" id="archivoContainer" style="display: none;">
              <label for="archivo" class="form-label">Subir Archivo</label>
              <select id="tipoArchivo" name="tipoArchivo" class="form-control mb-2" required>
                <option value="imagen">Imagen</option>
                <option value="pdf">PDF</option>
              </select>
              <input type="file" class="form-control" id="archivo" name="archivo">
            </div>

            <div class="mb-3">
              <label for="mensaje" class="form-label">Mensaje</label>
              <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="Escriba su mensaje" required></textarea>
            </div>

            <div class="mb-3">
              <label for="destinatario" class="form-label">Destinatario</label>
              <input type="text" class="form-control" id="destinatario" name="destinatario" placeholder="Ingrese destinatario" required>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile-menu.js"></script>
  <script src="../js/mensajes.js"></script>

  <script>
    // Obtener el formulario y los elementos relevantes
    const form = document.getElementById("formRedactarMensaje");
    const tipoMensaje = document.getElementById("tipoMensaje");
    const archivoContainer = document.getElementById("archivoContainer");
    const archivoInput = document.getElementById("archivo");

    // Evento para mostrar/ocultar el campo de archivo basado en el tipo de mensaje
    document.getElementById("tipoMensaje").addEventListener("change", function() {
      if (tipoMensaje.value === "Documentacion") {
        archivoContainer.style.display = "block";
        archivoInput.required = true; // Hacer que sea obligatorio
      } else {
        archivoContainer.style.display = "none";
        archivoInput.required = false; // No obligatorio si no es documentación
      }
    });

    form.addEventListener("submit", function(event) {
      const destinatario = document.getElementById("destinatario").value.trim();
      const tituloMensaje = document.getElementById("tituloMensaje").value.trim();
      const mensaje = document.getElementById("mensaje").value.trim();
      const tipoMensajeSeleccionado = tipoMensaje.value;

      // Validar que el archivo esté presente si es "Documentación"
      if (tipoMensajeSeleccionado === "Documentacion" && archivoInput.files.length === 0) {
        alert("Debe adjuntar un archivo para la documentación.");
        event.preventDefault(); // Solo detener si falta el archivo
        return;
      }

      if (!destinatario || !tituloMensaje || !mensaje) {
        alert("Por favor, complete todos los campos.");
        event.preventDefault(); // Evitar envío si falta información
        return;
      }

      if (destinatario && tituloMensaje && mensaje) {
        alert("Mensaje enviado a " + destinatario);

        // Resetear formulario
        archivoContainer.style.display = "none"; // Ocultar el campo de archivo nuevamente

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById("redactarMensajeModal"));
        modal.hide();
      } else {
        alert("Por favor, complete todos los campos.");
      }
    });
  </script>



</body>

</html>