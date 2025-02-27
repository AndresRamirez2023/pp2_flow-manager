<?php
require_once '../../classes/Usuario.php';
require_once __DIR__ . '/../../repositories/Repositorio_Solicitud.php';
require_once __DIR__ . '/../../controllers/Controlador_Solicitud.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    // Redirigir con un mensaje de error si no está logueado
    header('Location: login.php?mensaje=Error: Debes iniciar sesión');
    exit();
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

          <?php if ($isRRHH): // Solo mostrar el botón si es RRHH ?>
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

  <!-- Verificar si no es RRHH y mostrar formulario para solicitar licencia -->
  <?php if (!$isRRHH): ?>
    <div class="container-fluid">
      <div class="row full-view">
        <main class="col-md-12 main-content">
          <div class="container my-4 p-4 bg-white rounded shadow-sm">
            <h2>Solicitar Licencia</h2>
            <!-- Formulario de solicitud de licencia horizontal -->
            <form id="form-licencia" class="row g-3" action="../../controllers/Controlador_Solicitud.php" method="POST">
              <!-- Campo oculto con el DNI del usuario logueado -->
              <input type="hidden" name="DniSolicitante" value="<?php echo $usuario->getDni(); ?>" />
              <!-- Tipo de Licencia -->
              <div class="col-md-3">
                <label for="tipoLicencia" class="form-label">Tipo de Licencia</label>
                <select class="form-select" name="tipoLicencia" id="tipoLicencia" required>
                  <option selected disabled>Seleccione una opción</option>
                  <option value="vacaciones">Vacaciones</option>
                  <option value="enfermedad">Enfermedad</option>
                  <option value="maternidad">Maternidad/Paternidad</option>
                  <option value="estudio">Estudio</option>
                  <option value="otros">Otros</option>
                </select>
              </div>
              <!-- Fecha de Inicio -->
              <div class="col-md-3">
                <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" required />
              </div>
              <!-- Fecha de Fin -->
              <div class="col-md-3">
                <label for="fechaFin" class="form-label">Fecha de Fin</label>
                <input type="date" class="form-control" name="fechaFin" id="fechaFin" required />
              </div>
              <!-- Botón Enviar -->
              <div class="col-md-3 d-flex align-items-end">
                <button type="submit" name="accion" value="crear" class="btn btn-custom w-100">Enviar Solicitud</button>
              </div>
            </form>
          </div>
        </main>
      </div>
    </div>
  <?php endif; ?>

  <!-- Historial de licencias como tabla -->
  <div class="container my-4 p-4 bg-white rounded shadow-sm">
  <h2>Historial de Licencias</h2>
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th scope="col">Tipo de Licencia</th>
        <?php if ($isRRHH): ?>
          <th scope="col">Dni solicitante</th> <!-- Columna solo para RRHH -->
        <?php endif; ?>
        <th scope="col">Fecha de Inicio</th>
        <th scope="col">Fecha de Fin</th>
        <th scope="col">Estado</th> <!-- Columna para el estado -->
        <?php if ($isRRHH): ?>
          <th scope="col">Acciones</th> <!-- Columna solo para RRHH -->
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $solicitudes = $solicitudes ?? [];
      // Aquí debes asegurarte de que la variable $solicitudes tenga los datos adecuados
      if ($solicitudes !== false && !empty($solicitudes)) {
        foreach ($solicitudes as $solicitud) {
          echo "<tr>";
          echo "<td>{$solicitud['TipoSolicitud']}</td>";
          if ($isRRHH): 
            echo "<td>{$solicitud['DniSolicitante']}</td>";
          endif;          
          echo "<td>{$solicitud['FechaHoraDesde']}</td>";
          echo "<td>{$solicitud['FechaHoraHasta']}</td>";
          echo "<td>{$solicitud['Estado']}</td>";
          
          // Si es RRHH, mostrar columna de acciones
          if ($isRRHH) {
            echo "<td>
                    <form action='../../controllers/Controlador_Solicitud.php' method='post'>
                        <input type='hidden' name='DniSolicitante' value='{$solicitud['DniSolicitante']}'>
                        <input type='hidden' name='id_licencia' value='" . (isset($solicitud['id_licencia']) ? $solicitud['id_licencia'] : '') . "'>

                        <select name='estado'>
                            <option value='Pendiente' " . ($solicitud['Estado'] == 'Pendiente' ? 'selected' : '') . ">Pendiente</option>
                            <option value='Aprobada' " . ($solicitud['Estado'] == 'Aprobada' ? 'selected' : '') . ">Aprobada</option>
                            <option value='Rechazada' " . ($solicitud['Estado'] == 'Rechazada' ? 'selected' : '') . ">Rechazada</option>
                        </select>
                        <button type='submit' name='accion' value='actualizar'>Actualizar</button>
                    </form>
                  </td>";
          }
          

          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='5'>No se encontraron solicitudes</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

  <!-- Bootstrap 5 JS -->
  <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile-menu.js"></script>
</body>

</html>
