<?php
require_once '../../classes/Usuario.php';
require_once __DIR__ . '/../../repositories/Repositorio_Departamento.php';
require_once __DIR__ . '/../../repositories/Repositorio_Usuario.php';
session_start();



if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

$usuario = unserialize($_SESSION['usuario']);

// Verificar si el usuario es de tipo RRHH
if (!$usuario->esRRHH()) {
  // Si no es RRHH, redirigir a una página de inicio
  header("Location: panelPrincipal.php");
  exit;
}
$rd = new Repositorio_Departamento();
$departamentos = $rd->get_all();
$departamentos_nombres = [];

foreach ($departamentos as $departamento) {
  $nombre = $departamento->getNombre();
  $nombre_departamento = strstr($nombre, '_') ? substr(strstr($nombre, '_'), 1) : $nombre;
  $departamentos_nombres[] = [$departamento, $nombre_departamento];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión &#65381; Flow Manager</title>
  <link rel="icon" href="../img/Icon - FlowManager.png">

  <!-- Bootstrap 5 CSS -->
  <link
    href="../../assets/dist/css/bootstrap.min.css"
    rel="stylesheet" />

  <!-- Bootstrap Icons CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    rel="stylesheet" />

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
          <a class="btn btn-custom nav-link" href="gestion.php">Gestión</a>
          <a class="btn btn-custom nav-link" href="mensajes.php">Mensajes y Archivos</a>
          <a class="btn btn-custom nav-link" href="departamento.php">Departamento</a>
          <a class="btn btn-custom nav-link" href="licencias.php">Licencias y Vacaciones</a>
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
        <!-- Sección para agregar usuarios -->
        <div class="container my-4 bg-white rounded shadow-sm">
          <h2>Gestión de Usuarios</h2>
          <hr>
          <h3>Agregar usuario</h3>

          <form
            id="form-agregar-usuario"
            class="row g-3"
            action="../../controllers/controlador_usuario.php"
            method="POST"
            onsubmit="return validarFormulario();">

            <div class="col-md-6">
              <label for="nombreApellido" class="form-label">Nombre y Apellido<b>*</b></label>
              <input
                type="text"
                class="form-control"
                id="nombreApellido"
                name="nombreApellido"
                pattern="^[A-Za-zÀ-ÿ\s]{2,50}$"
                title="El nombre debe contener solo letras y espacios, entre 2 y 50 caracteres."
                required />
            </div>

            <div class="col-md-6">
              <label for="dni" class="form-label">DNI <b>*</b></label>
              <input
                type="text"
                class="form-control"
                id="dni"
                name="dni"
                maxlength="8"
                pattern="\d{8}"
                title="El DNI debe tener exactamente 8 dígitos numéricos."
                required />
            </div>

            <div class="col-md-6">
              <label for="domicilio" class="form-label">Domicilio</label>
              <input
                type="text"
                class="form-control"
                id="domicilio"
                name="domicilio"
                pattern="^[A-Za-z0-9\s,.-]{5,100}$"
                title="El domicilio debe contener entre 5 y 100 caracteres, incluyendo letras, números, espacios, y símbolos como ',' o '-'." />
            </div>

            <div class="col-md-6">
              <label for="telefono" class="form-label">Número de Teléfono</label>
              <input
                type="tel"
                class="form-control"
                id="telefono"
                name="telefono"
                pattern="^\+?\d{7,15}$"
                title="El teléfono debe contener entre 7 y 15 dígitos, opcionalmente iniciando con '+'." />
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email <b>*</b></label>
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                required />
            </div>

            <div class="col-md-6">
              <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
              <input
                type="date"
                class="form-control"
                id="fechaNacimiento"
                name="fechaNacimiento"
                max="2006-12-31"
                title="El usuario debe ser mayor de edad." />
            </div>

            <div class="col-md-6">
              <label for="TipoDeUsuario" class="form-label">Tipo de usuario <b>*</b></label>
              <select name="TipoDeUsuario" id="TipoDeUsuario" class="form-select" required>
                <option value="">Seleccione un tipo de usuario</option>
                <option value="RRHH">RRHH</option>
                <option value="Directivo">Directivo</option>
                <option value="Empleado">Empleado</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="Departamento" class="form-label">Departamento</label>
              <?php
              echo '<select name="Departamento" id="Departamento" class="form-select">';
              echo '<option value="Sin Asignar">Seleccione un departamento</option>';

              foreach ($departamentos_nombres as $departamento) {
                if ($departamento[1] === 'Sin asignar') {
                  continue;
                }
                // Selecciona un departamento en caso de editar usuario
                // $selected = '' . $usuario ? ($usuario->getDepartamento()->getNombre() ? 'selected' : '') : '';
                echo '<option value="' . $departamento[0]->getNombre() . '">' . $departamento[1] . '</option>';
              }
              echo '</select>';
              ?>
            </div>
            <div class="col-md-12 col-relative" style="min-height: 70px;">
              <button type="submit" class="btn btn-primary button-absolute">Agregar Usuario</button>
            </div>
          </form>
          <hr>
          <div class="container my-4 bg-white rounded shadow-sm w-50">
            <h2>Buscador de Usuarios</h2>
            <input type="text" id="buscador" class="form-control" placeholder="Buscar usuario por nombre o DNI">
            <!-- Tabla para mostrar los usuarios -->
            <table class="table table-striped mt-3">
              <thead>
                <tr>
                  <th>DNI</th>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody id="resultados">
                <!-- filas generadas dinámicamente -->
              </tbody>
            </table>
          </div>
        </div>

        <!-- Contenedor principal -->
        <div class="container my-4 bg-white rounded shadow-sm">
          <h2>Gestión de Departamentos</h2>
          <hr>
          <h3>Agregar departamento</h3>

          <!-- Fila con dos columnas -->
          <div class="row">
            <!-- Columna izquierda: Agregar departamento -->
            <div class="col-12 mb-3">
              <form id="form-agregar-departamento" class="row g-3" action="../../controllers/Controlador_Departamento.php" method="POST">
                <div class="col-5">
                  <label for="nombreDepartamento" class="form-label">Nombre del Departamento</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nombreDepartamento"
                    name="nombreDepartamento"
                    required />
                </div>
                <div class="col-5">
                  <label for="dniDirector" class="form-label">DNI del Director</label>
                  <input
                    type="text"
                    class="form-control"
                    id="dniDirector"
                    name="dniDirector"
                    required />
                </div>
                <input type="hidden" name="accion" value="agregar">
                <div class="col-2 col-relative">
                  <button type="submit" class="btn btn-primary button-absolute">
                    Agregar
                  </button>
                </div>
              </form>
            </div>
            <div class="col-md-12">
              <hr>
              <h4>Departamentos existentes</h4>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th colspan="2">Director a cargo</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (count($departamentos) === 0) {
                      echo '<tr>
                  <th colspan="6" class="text-center"><h3>No se agregaron departamentos al sistema</h3></th>
                  </tr>';
                    } else {
                      foreach ($departamentos_nombres as $departamento): ?>
                        <?php
                        if ($departamento[1] === 'Sin asignar') {
                          continue;
                        }
                        ?>
                        <tr>
                          <td><?php echo htmlspecialchars($departamento[1]); ?></td>
                          <td><?php echo htmlspecialchars($departamento[0]->getDirectorAcargo()->getNombreApellido()); ?></td>
                          <td><?php echo htmlspecialchars($departamento[0]->getDirectorAcargo()->getDni()); ?></td>
                          <td>
                            <button type='button' class='btn btn-primary btn-sm'
                              onclick="mostrarFormularioEditar(<?php echo htmlspecialchars($departamento[0]->getNombre()) ?>,<?php echo htmlspecialchars($departamento[0]->getDirectorAcargo()->getDni()) ?>)">Editar</button>
                            <button type='button' class='btn btn-danger btn-sm'>Borrar</button>
                          </td>
                      <?php endforeach;
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Sección para enviar avisos o documentación -->
        <div class="container my-4 bg-white rounded shadow-sm">
          <h2>Enviar Aviso o Documentación</h2>
          <form id="form-enviar-aviso-doc">
            <!-- Radio buttons para seleccionar tipo -->
            <div class="mb-3">
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  name="tipoEnvio"
                  id="envioAviso"
                  value="aviso"
                  checked />
                <label class="form-check-label" for="envioAviso">Aviso</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  name="tipoEnvio"
                  id="envioDocumento"
                  value="documento" />
                <label class="form-check-label" for="envioDocumento">Documentación</label>
              </div>
            </div>

            <!-- Sección de envío de aviso -->
            <div id="seccion-aviso" class="row">
              <div class="col-6">
                <div class="mb-3">
                  <label for="tituloAviso" class="form-label">Título del Aviso</label>
                  <input
                    type="text"
                    class="form-control"
                    id="tituloAviso"
                    required />
                </div>
                <div class="mb-3">
                  <label for="cuerpoAviso" class="form-label">Cuerpo del Mensaje</label>
                  <textarea
                    class="form-control"
                    id="cuerpoAviso"
                    rows="4"
                    required></textarea>
                </div>
              </div>

              <!-- Ajuste del select dentro de la columna -->
              <div class="col-6">
                <div class="mb-3" style="height: 100%">
                  <label for="departamentosAviso" class="form-label">Departamentos</label>
                  <select
                    class="form-select"
                    id="departamentosAviso"
                    multiple
                    style="height: 200px">
                    <option value="todos">Todos</option>
                    <option value="ventas">Ventas</option>
                    <option value="finanzas">Finanzas</option>
                    <option value="rrhh">Recursos Humanos</option>
                    <option value="it">IT</option>
                    <!-- Agregar más departamentos si es necesario -->
                  </select>
                </div>
              </div>
            </div>

            <!-- Sección de envío de documentación -->
            <div id="seccion-documento" class="d-none">
              <div class="mb-3">
                <label for="usuarioDocumento" class="form-label">Usuario (DNI)</label>
                <input
                  type="text"
                  class="form-control"
                  id="usuarioDocumento"
                  required />
              </div>
              <div class="mb-3">
                <label for="archivoDocumento" class="form-label">Archivo</label>
                <input
                  type="file"
                  class="form-control"
                  id="archivoDocumento"
                  required />
              </div>
              <div class="mb-3">
                <label for="mensajeDocumento" class="form-label">Mensaje Opcional</label>
                <textarea
                  class="form-control"
                  id="mensajeDocumento"
                  rows="4"></textarea>
              </div>
              <div class="mb-3 form-check">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="requiereFirma" />
                <label class="form-check-label" for="requiereFirma">Requiere firma</label>
              </div>
            </div>

            <!-- Botón de envío -->
            <button type="submit" class="btn btn-primary w-100">
              Enviar
            </button>
          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile-menu.js"></script>
  <script src="../js/gestion.js"></script>
  <script src="../js/buscar_usuarios_tabla.js"></script>
</body>

</html>