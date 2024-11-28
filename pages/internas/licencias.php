<?php
require_once '../../classes/Usuario.php'; 
// Asegúrate de iniciar la sesión
session_start();

// Verificar si la sesión 'usuario' está definida
if (!isset($_SESSION['usuario'])) {
    // Redirigir con un mensaje de error si no está logueado
    header('Location: login.php?mensaje=Error: Debes iniciar sesión');
    exit();
}

// Deserializar los datos del usuario de la sesión
$usuario = unserialize($_SESSION['usuario']);

// Ahora puedes acceder al DNI directamente
$dni = $usuario->getDni(); // Si tienes un getter en la clase Usuario
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Licencias &#65381; Flow Manager</title>
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
      <!-- Contenido principal -->
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
              <button type="submit" class="btn btn-custom w-100">Enviar Solicitud</button>
            </div>
          </form>

        </div>

        <!-- Historial de licencias como tabla -->
        <div class="container my-4 p-4 bg-white rounded shadow-sm">
          <h2>Historial de Licencias</h2>
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th scope="col">Tipo de Licencia</th>
                <th scope="col">Fecha de Inicio</th>
                <th scope="col">Fecha de Fin</th>
                <th scope="col">Estado</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ejemplo de una licencia aprobada -->
              <tr>
                <td>Vacaciones</td>
                <td>01/07/2024</td>
                <td>15/07/2024</td>
                <td><span class="badge bg-aprove">Aprobada</span></td>
              </tr>
              <tr>
                <td>Enfermedad</td>
                <td>20/08/2024</td>
                <td>25/08/2024</td>
                <td><span class="badge bg-pending">Pendiente</span></td>
              </tr>
              <!-- Ejemplo de una licencia rechazada -->
              <tr>
                <td>Vacaciones</td>
                <td>10/08/2024</td>
                <td>15/08/2024</td>
                <td><span class="badge bg-danger">Rechazada</span></td>
              </tr>
              <!-- Agrega más filas según sea necesario -->
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile-menu.js"></script>
</body>

</html>