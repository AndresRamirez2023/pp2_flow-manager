<?php
require_once '../../classes/Usuario.php';
require_once '../../controllers/Controlador_Departamento.php';
require_once '../../controllers/controlador_usuario.php';
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

$cd = new Controlador_Departamento();

$cu = new Controlador_Usuario();

$departamento = $cd->get_by_name($usuario->getDepartamento()->getNombre());

// TODO: Línea para versión de pruebas, borrar unserialize usuario para versión final
$empresa = isset($_SESSION['empresa']) ? unserialize($_SESSION['empresa']) : $usuario->getDepartamento()->getEmpresa();

// Función para obtener la extensión del archivo
function obtenerExtension($nombreArchivo)
{
  return strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
}

if ($empresa) {
  $nombre_empresa = $empresa->getNombre();
  $nombre_empresa_limpio = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombre_empresa);

  function encontrarArchivos($directorio, $nombre_base)
  {
    $extensiones_posibles = ['png', 'jpg', 'jpeg', 'svg', 'gif', 'pdf'];
    foreach ($extensiones_posibles as $ext) {
      $ruta = "$directorio/$nombre_base.$ext";
      if (file_exists($ruta)) {
        return $ruta;
      }
    }
    return null;
  }

  $path_archivo1 = encontrarArchivos("../../uploads/$nombre_empresa_limpio/files", "ArchivoInicio1");
  $path_archivo2 = encontrarArchivos("../../uploads/$nombre_empresa_limpio/files", "ArchivoInicio2");
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
            <a class="btn btn-custom nav-link" href="gestion.php">Gestión</a>
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

  <div class="container-fluid">
    <div class="row full-view">
      <!-- Contenido principal -->
      <main class="main-content">
        <div class="container">

          <!-- Documentos informativos -->
          <div class="row full-view">
            <h1>Bienvenido al sistema de gestión de <b><?php echo isset($nombre_empresa) ? $nombre_empresa : 'empresa' ?></b></h1>

            <hr>

            <div class="col-md-6">
              <!-- Calendario -->
              <h2>Calendario Laboral</h2>
              <div class="justify-content-center">
                <div id="calendar" class="mb-4 w-75"></div>
              </div>

              <hr>

              <h2>Tu departamento:</h2>
              <?php
              $nombre_departamento = $departamento->getNombre();
              $nombre = strstr($nombre_departamento, '_') ? substr(strstr($nombre_departamento, '_'), 1) : $nombre_departamento;
              ?>
              <ul>
                <li>Nombre: <b><?php echo $nombre ?></b><br></li>
                <li>Director a cargo: <b><?php echo $departamento->getDirectorAcargo()->getNombreApellido() ?></b><br></li>
                <li>Mail de contacto: <b><?php echo $departamento->getDirectorAcargo()->getCorreoElectronico() ?></b><br></li>
                <li>Cantidad de integrantes: <b><?php echo count($cu->get_by_param($departamento)) ?></b><br></li>
              </ul>
            </div>
            <div class="col-md-6">
              <h2>Avisos/Documentos</h2>
              <?php
              if (isset($path_archivo1)) {
                if (obtenerExtension($path_archivo1) !== 'pdf') {
                  echo '<img src="' . $path_archivo1 . '" alt="Aviso/documento de la empresa" class="img-fluid"/>';
                } else {
                  echo '<iframe
                  src="' . $path_archivo1 . '#toolbar=0&view=FitHW&navpanes=0"
                  scrolling="no"></iframe>';
                }
              } else {
                echo '<img src="../img/sin_imagen.png" alt="No hay archivos disponibles" class="img-fluid"/>';
              }
              ?>
            </div>
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