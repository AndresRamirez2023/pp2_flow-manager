<?php
require_once '../../classes/Usuario.php';
require_once __DIR__ . '/../../controllers/Controlador_Solicitud.php';

session_start();

if (!isset($_SESSION['usuario'])) {
  header('Location: login.php?mensaje=Error: Debes iniciar sesión');
  exit;
}

$usuario = unserialize($_SESSION['usuario']);

$isRRHH = $usuario->esRRHH();

$cs = new Controlador_Solicitud();

if ($isRRHH) {
  $solicitudes = $cs->get_all();
} else {
  $solicitudes = $cs->get_all($usuario->getDni());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Datos de la solicitud
  $dni_solicitante = trim($_POST['DniSolicitante']);
  $tipo_solicitud = trim($_POST['tipoSolicitud']);
  $fecha_desde = trim($_POST['fechaDesde']);
  $fecha_hasta = trim($_POST['fechaHasta']);
  $estado = isset($_POST['estado']) ? trim($_POST['estado']) : null;

  if (empty($dni_solicitante) || empty($tipo_solicitud) || empty($fecha_desde) || empty($fecha_hasta) || (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') && empty($estado)) {
    $_SESSION['mensaje'] = "Hay campos obligatorios incompletos. Revise los datos ingresados.";
    $_SESSION['mensaje_tipo'] = "warning";
    header('Location: licencias.php');
    exit();
  }

  // Objeto Solicitud
  $solicitud = new Solicitud(
    $tipo_solicitud,
    $dni_solicitante,
    $fecha_desde,
    $fecha_hasta,
    $estado
  );

  if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $result = $cs->update($solicitud);
  } elseif (isset($_POST['accion']) && $_POST['accion'] === 'solicitar') {
    $result = $cs->create($solicitud);
  } else {
    $_SESSION['mensaje'] = "<b>Error</b>. Debe actualizar o solicitar una licencia.";
    $_SESSION['mensaje_tipo'] = "danger";
    exit();
  }

  if ($result) {
    if ($_POST['accion'] === 'actualizar') {
      $_SESSION['mensaje'] = 'Licencia de <b>' . $dni_solicitante . '</b> se actualizó a <b>' . $estado . '</b>.';
    } else {
      $_SESSION['mensaje'] = 'Licencia de tipo <b>' . $tipo_solicitud . '</b> enviada con éxito, aguarde a que RRHH la revise.';
    }
    $_SESSION['mensaje_tipo'] = "info";
  } else {
    if (!isset($_SESSION['mensaje']) && !isset($_SESSION['mensaje_tipo'])) {
      $_SESSION['mensaje'] = "<b>Error</b> al actualizar/solicitar la licencia. Verifique los datos, si el problema persiste <b>contacte a un administrador</b>.";
      $_SESSION['mensaje_tipo'] = "danger";
    }
  }
  header('Location: licencias.php');
  exit();
}
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
  <link rel="stylesheet" href="../css/internas.css" />
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
            <a class="btn btn-custom-rrhh nav-link" href="gestion.php">Gestión</a>
          <?php endif; ?>
          <a
            href="soporte.php"
            class="btn btn-help"
            role="button"
            aria-label="Soporte">
            <i class="bi bi-question-circle"></i>
          </a>
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
  <div class="container-fluid">
    <div class="row full-view">
      <main class="col-md-12 main-content">
        <?php if (!$isRRHH): ?>
          <div class="container my-4 p-4 bg-white rounded shadow-sm">
            <h2>Solicitar Licencia</h2>
            <form id="form-licencia" action='licencias.php' method='post' class="row g-3">
              <div class="col-md-3">
                <label for="tipoLicencia" class="form-label">Tipo de Licencia</label>
                <select class="form-select" id="tipoSolicitud" name="tipoSolicitud" required>
                  <option selected disabled>Seleccione una opción</option>
                  <option value="vacaciones">Vacaciones</option>
                  <option value="enfermedad">Enfermedad</option>
                  <option value="maternidad">Maternidad/Paternidad</option>
                  <option value="estudio">Estudio</option>
                  <option value="otros">Otros</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="fechaDesde" class="form-label">Fecha de Inicio</label>
                <input
                  type="date"
                  class="form-control"
                  id="fechaDesde"
                  name="fechaDesde"
                  required />
              </div>
              <div class="col-md-3">
                <label for="fechaHasta" class="form-label">Fecha de Fin</label>
                <input
                  type="date"
                  class="form-control"
                  id="fechaHasta"
                  name="fechaHasta"
                  required />
              </div>
              <input type='hidden' name='DniSolicitante' value=<?php echo $usuario->getDni() ?>>
              <div class="col-md-3 d-flex align-items-end">
                <button type="submit" name='accion' value='solicitar' class="btn btn-custom w-100">
                  Enviar Solicitud
                </button>
              </div>
            </form>
          </div>
        <?php endif; ?>


        <!-- Historial de licencias como tabla -->
        <div class="container my-4 p-4 bg-white rounded shadow-sm">
          <h2>Historial de Licencias</h2>
          <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?php echo $_SESSION['mensaje_tipo']; ?> mt-3">
              <?php echo $_SESSION['mensaje']; ?>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
            ?>
          <?php endif; ?>
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <?php if ($isRRHH): ?>
                  <th scope="col">Dni solicitante</th> <!-- Columna solo para RRHH -->
                <?php endif; ?>
                <th scope="col">Tipo de Licencia</th>
                <th scope="col">Fecha de Inicio</th>
                <th scope="col">Fecha de Fin</th>
                <th scope="col">Estado</th>
                <?php if ($isRRHH): ?>
                  <th scope="col">Acciones</th> <!-- Columna solo para RRHH -->
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php
              if (count($solicitudes) === 0) {
                echo '<tr>
                        <th colspan="6" class="text-center"><h3>No se solicitaron licencias aún</h3></th>
                      </tr>';
              } else {
                foreach ($solicitudes as $solicitud): ?>
                  <tr>
                    <?php if ($isRRHH): ?>
                      <td><?php echo htmlspecialchars($solicitud->getDniSolicitante()); ?></td>
                    <?php endif; ?>
                    <td><?php echo htmlspecialchars($solicitud->getTipoSolicitud()); ?></td>
                    <td><?php echo htmlspecialchars($solicitud->getFechaDesde()); ?></td>
                    <td><?php echo htmlspecialchars($solicitud->getFechaHasta()); ?></td>
                    <td class="<?php echo $solicitud->getEstado() === 'Rechazada' ? 'bg-danger text-white' : ($solicitud->getEstado() === 'Pendiente' ? 'bg-warning text-dark' : ($solicitud->getEstado() === 'Aprobada' ? 'bg-success text-white' : ''));
                      ?>">
                      <?php echo htmlspecialchars($solicitud->getEstado()); ?></td>
                    <?php if ($isRRHH): ?>
                      <td>
                        <form action='licencias.php' method='post'>
                          <input type='hidden' name='DniSolicitante' value=<?php echo $solicitud->getDniSolicitante() ?>>
                          <input type='hidden' name='tipoSolicitud' value=<?php echo $solicitud->getTipoSolicitud() ?>>
                          <input type='hidden' name='fechaDesde' value=<?php echo $solicitud->getFechaDesde() ?>>
                          <input type='hidden' name='fechaHasta' value=<?php echo $solicitud->getFechaHasta() ?>>
                          <div class="row">
                            <div class="col-6">
                              <select name='estado' class="form-select">
                                <option value='Pendiente' <?php echo $solicitud->getEstado() == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                <option value='Aprobada' <?php echo $solicitud->getEstado() == 'Aprobada' ? 'selected' : '' ?>>Aprobada</option>
                                <option value='Rechazada' <?php echo $solicitud->getEstado() == 'Rechazada' ? 'selected' : '' ?>>Rechazada</option>
                              </select>
                            </div>
                            <div class="col-6">
                              <button type='submit' name='accion' value='actualizar' class="btn custom-color w-100">Actualizar</button>
                            </div>
                          </div>
                        </form>
                      </td>
                    <?php endif; ?>
                  </tr>
              <?php endforeach;
              } ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src=" ../../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile-menu.js"></script>
</body>

</html>