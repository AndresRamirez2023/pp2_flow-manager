<?php
require_once '../../classes/Usuario.php';
require_once '../../classes/Empresa.php';
require_once '../../classes/Departamento.php';
require_once __DIR__ . '/../../controllers/Controlador_Departamento.php';
require_once __DIR__ . '/../../controllers/Controlador_Empresa.php';
require_once __DIR__ . '/../../controllers/controlador_usuario.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_SESSION['mensaje_mostrado'])) {
  unset($_SESSION['formulario']);
  unset($_SESSION['usuarioEditar']);
  unset($_SESSION['departamentoEditar']);
  $_SESSION['mensaje_mostrado'] = true;
}

if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

// Declaración de variables
$usuario = unserialize($_SESSION['usuario']);
// TODO: Línea para versión de pruebas, borrar unserialize usuario para versión final
$empresa = isset($_SESSION['empresa']) ? unserialize($_SESSION['empresa']) : $usuario->getDepartamento()->getEmpresa();
$usuario_editar = null;
$departamento_editar = null;

$cd = new Controlador_Departamento();
$ce = new Controlador_Empresa();
$cu = new Controlador_Usuario();

$departamentos = $cd->get_all($empresa->getNombre());
$departamentos_nombres = [];

$nombre_empresa = $empresa->getNombre();

// Verificar si el usuario es de tipo RRHH
if (!$usuario->esRRHH()) {
  // Si no es RRHH, redirigir a una página de inicio
  header("Location: panelPrincipal.php");
  exit;
}

if (isset($_GET['dni'])) {
  $usuario_editar = $cu->get_by_param($_GET['dni']);
  $_SESSION['usuarioEditar'] = serialize($usuario_editar);
}

if (isset($_GET['departamento'])) {
  $departamento_editar = $cd->get_by_name($_GET['departamento']);
  $_SESSION['departamentoEditar'] = serialize($departamento_editar);
}

foreach ($departamentos as $departamento) {
  $nombre = $departamento->getNombre();
  $nombre_departamento = strstr($nombre, '_') ? substr(strstr($nombre, '_'), 1) : $nombre;
  $departamentos_nombres[] = [$departamento, $nombre_departamento];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregarUsuario') {
  $_SESSION['formulario'] = 'usuario';
  if (isset($_SESSION['usuarioEditar'])) {
    $usuario_editar = $_SESSION['usuarioEditar'];
  }
  // Datos del usuario
  $nombre_apellido = trim($_POST['nombreApellido']);
  $dni = trim($_POST['dni']);
  $domicilio = trim($_POST['domicilio']);
  $telefono = trim($_POST['telefono']);
  $email = trim($_POST['email']);
  $fecha_de_nac = trim($_POST['fechaNacimiento']);
  $tipo_de_usuario = trim($_POST['tipoDeUsuario']);
  $nombre_departamento = trim($_POST['Departamento']);
  $clave_generada = preg_replace('/[^A-Za-z0-9-]/', '.', strtolower($nombre_empresa)) . "123";

  $nextNumber = 4;
  while (strlen($clave_generada) < 8) {
    $clave_generada .= $nextNumber;
    $nextNumber++;
  }

  if (empty($dni) || empty($nombre_apellido) || empty($email) || empty($tipo_de_usuario)) {
    $_SESSION['mensaje'] = "Hay campos obligatorios incompletos. Revise los datos ingresados.";
    $_SESSION['mensaje_tipo'] = "warning";
    header('Location: gestion.php' . ($usuario_editar ? '?dni=' . $usuario_editar->getDni() : ''));
    exit();
  }

  if (!$usuario_editar && $cu->get_by_param($dni) !== null) {
    $_SESSION['mensaje'] = "El usuario <b>ya se encuentra registrado</b> en el sistema. Revise los datos ingresados y/o los usuarios creados anteriormente.";
    $_SESSION['mensaje_tipo'] = "danger";
    header('Location: gestion.php');
    exit();
  }

  // Buscar Departamento
  $empresa = new Empresa($nombre_empresa);
  $departamento = $cd->get_by_name($nombre_departamento);

  if ($departamento == null) {
    $_SESSION['mensaje'] = "El departamento <b>no existe</b> en el sistema. Revise los datos ingresados.";
    $_SESSION['mensaje_tipo'] = "danger";
    header('Location: gestion.php' . ($usuario_editar ? '?dni=' . $usuario_editar->getDni() : ''));
    exit();
  }

  // Objeto Usuario
  $usuario = new Usuario(
    $dni,
    $email,
    $tipo_de_usuario,
    $departamento,
    $nombre_apellido,
    $fecha_de_nac,
    $domicilio,
    $telefono
  );

  if ($usuario_editar) {
    $result = $cu->update($usuario, null);
  } else {
    $result = $cu->save($usuario, $clave_generada);
  }

  if ($result) {
    unset($_SESSION['usuarioEditar']);
    $_SESSION['mensaje'] = "Usuario <b>" . ($usuario_editar ? 'editado correctamente</b>.' : 'creado correctamente</b> con la clave provisoria: <b>' . $clave_generada . '</b>.');
    $_SESSION['mensaje_tipo'] = "info";
  } else {
    if (!isset($_SESSION['mensaje']) && !isset($_SESSION['mensaje_tipo'])) {
      $_SESSION['mensaje'] = "<b>Error</b> al crear el usuario. Verifique los datos, si el problema persiste <b>contacte a un administrador</b>.";
      $_SESSION['mensaje_tipo'] = "danger";
    }
  }

  header('Location: gestion.php');
  exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregarDepartamento') {
  $_SESSION['formulario'] = 'departamento';
  if (isset($_SESSION['departamentoEditar'])) {
    $departamento_editar = $_SESSION['departamentoEditar'];
  }
  // Datos del departamento
  $nombre_departamento = trim($_POST['nombreDepartamento']);
  $dni_director = trim($_POST['dniDirector']);
  $empresa = new Empresa($nombre_empresa);

  if (empty($nombre_departamento)) {
    $_SESSION['mensaje'] = "Hay campos obligatorios incompletos. Revise los datos ingresados.";
    $_SESSION['mensaje_tipo'] = "warning";
    header('Location: gestion.php' . ($departamento_editar ? '?departamento=' . $departamento_editar->getNombre() : '') . '#departamentos');
    exit();
  }

  $nombre_completo = $nombre_empresa  . "_" . $nombre_departamento;

  if (!$departamento_editar && $cd->get_by_name($nombre_completo) !== null) {
    $_SESSION['mensaje'] = "El departamento <b>ya se encuentra registrado</b> en el sistema. Revise los datos ingresados y/o los departamentos creados anteriormente.";
    $_SESSION['mensaje_tipo'] = "danger";
    header('Location: gestion.php#departamentos');
    exit();
  }

  // Buscar Usuario
  if ($dni_director) {
    $usuario_a_cargo = $cu->get_by_param($dni_director);
  }

  if ($dni_director && $usuario_a_cargo == null) {
    $_SESSION['mensaje'] = "El usuario <b>no existe</b> en el sistema. Revise los datos ingresados.";
    $_SESSION['mensaje_tipo'] = "danger";
    header('Location: gestion.php' . ($departamento_editar ? '?departamento=' . $departamento_editar->getNombre() : '') . '#departamentos');
    exit();
  }

  // Objeto Departamento
  $departamento = new Departamento(
    $nombre_completo,
    $usuario_a_cargo,
    $empresa
  );

  if ($departamento_editar) {
    $old_departamento = $cd->get_by_name($usuario_a_cargo->getDepartamento()->getNombre());
    // var_dump('old '. $old_departamento->getNombre(), 'new '. $nombre_completo);
    // die;
    if ($nombre_completo !== $old_departamento->getNombre()) {
      $old_departamento->setDirectorAcargo(null);
      $cd->update($old_departamento);
    }
    $result = $cd->update($departamento);
  } else {
    $result = $cd->create($departamento);
  }

  if ($result) {
    unset($_SESSION['departamentoEditar']);
    $_SESSION['mensaje'] = 'Departamento <b>"' . $nombre_departamento . ($departamento_editar ? '"</b> editado correctamente' : '"</b> creado correctamente') . ($dni_director ? ' con el director: <b>"' . $dni_director . '"</b>.' : '.');
    $_SESSION['mensaje_tipo'] = "info";
  } else {
    if (!isset($_SESSION['mensaje']) && !isset($_SESSION['mensaje_tipo'])) {
      $_SESSION['mensaje'] = "<b>Error</b> al crear el departamento. Verifique los datos, si el problema persiste <b>contacte a un administrador</b>.";
      $_SESSION['mensaje_tipo'] = "danger";
    }
  }

  header('Location: gestion.php#departamentos');
  exit();
}

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
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
          <a class="btn btn-custom nav-link" href="mensajes.php">Mensajes y Archivos</a>
          <a class="btn btn-custom nav-link" href="departamento.php">Departamento</a>
          <a class="btn btn-custom nav-link" href="licencias.php">Licencias y Vacaciones</a>
          <a class="btn btn-custom-rrhh nav-link" href="gestion.php">Gestión</a>
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
      <main class="col-md-12 main-content">
        <!-- Sección para agregar usuarios -->
        <div class="container my-4 bg-white rounded shadow-sm">
          <h2>Gestión de Usuarios</h2>
          <hr>
          <?php
          if (!is_null($usuario_editar)) {
            echo '<h3>Editar usuario</h3>';
          } else {
            echo '<h3>Agregar usuario</h3>';
          }
          ?>

          <?php if (isset($_SESSION['mensaje']) && isset($_SESSION['formulario']) && $_SESSION['formulario'] == 'usuario'): ?>
            <div class="alert alert-<?php echo $_SESSION['mensaje_tipo']; ?> mt-3">
              <?php echo $_SESSION['mensaje']; ?>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
            ?>
          <?php endif; ?>
          <form
            id="form-agregar-usuario"
            class="row g-3"
            action="gestion.php"
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
                value="<?php echo $usuario_editar ? $usuario_editar->getNombreApellido() : '' ?>"
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
                value="<?php echo $usuario_editar ? $usuario_editar->getDni() : '' ?>"
                <?php echo $usuario_editar ? 'readonly' : '' ?>
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
                value="<?php echo $usuario_editar ? $usuario_editar->getDomicilio() : '' ?>"
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
                value="<?php echo $usuario_editar ? $usuario_editar->getTelefono() : '' ?>"
                title="El teléfono debe contener entre 7 y 15 dígitos, opcionalmente iniciando con '+'." />
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email <b>*</b></label>
              <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                value="<?php echo $usuario_editar ? $usuario_editar->getCorreoElectronico() : '' ?>"
                <?php echo $usuario_editar ? 'readonly' : '' ?>
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
                value="<?php echo $usuario_editar ? $usuario_editar->getFechaNac() : '' ?>"
                title="El usuario debe ser mayor de edad." />
            </div>

            <div class="col-md-6">
              <label for="tipoDeUsuario" class="form-label">Tipo de usuario <b>*</b></label>
              <select name="tipoDeUsuario" id="tipoDeUsuario" class="form-select" required>
                <option value="">Seleccione un tipo de usuario</option>
                <?php
                $tiposUsuario = ["RRHH", "Directivo", "Empleado"];
                foreach ($tiposUsuario as $tipo) {
                  $selected = (isset($usuario_editar) && $usuario_editar->getTipoUsuario() === $tipo) ? 'selected' : '';
                  echo "<option value='$tipo' $selected>$tipo</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-md-6">
              <label for="Departamento" class="form-label">Departamento</label>
              <select name="Departamento" id="Departamento" class="form-select">
                <option value="<?php echo $nombre_empresa . '_Sin asignar' ?>">Seleccione un departamento</option>

                <?php foreach ($departamentos_nombres as $departamento) :
                  if ($departamento[1] === 'Sin asignar') {
                    continue;
                  }
                  $nombre = $departamento[0]->getNombre();
                  $tiene_director = !is_null($departamento[0]->getDirectorAcargo());
                  $tipo_depto = str_contains($nombre, "Recursos Humanos") ? "Recursos Humanos" : "Otro";
                  $selected = ($usuario_editar && $usuario_editar->getDepartamento()->getNombre() === $nombre) ? 'selected' : '';
                ?>
                  <option value="<?= $nombre ?>" data-director="<?= $tiene_director ? 'true' : 'false' ?>" data-tipo="<?= $tipo_depto ?>" <?= $selected ?>>
                    <?= $departamento[1] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-12 col-relative" style="min-height: 70px;">
              <button type="submit" name="accion" value="agregarUsuario" class="btn custom-color button-absolute"><?php echo !is_null($usuario_editar) ? 'Guardar' : 'Agregar' ?></php></button>
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
          <h2 id="departamentos">Gestión de Departamentos</h2>
          <hr>
          <?php
          if (!is_null($departamento_editar)) {
            echo '<h3>Editar departamento</h3>';
          } else {
            echo '<h3>Agregar departamento</h3>';
          }
          ?>

          <?php if (isset($_SESSION['mensaje']) && isset($_SESSION['formulario']) && $_SESSION['formulario'] == 'departamento'): ?>
            <div class="alert alert-<?php echo $_SESSION['mensaje_tipo']; ?> mt-3">
              <?php echo $_SESSION['mensaje']; ?>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['mensaje_tipo']);
            ?>
          <?php endif; ?>
          <!-- Fila con dos columnas -->
          <div class="row">
            <!-- Columna izquierda: Agregar departamento -->
            <div class="col-12 mb-3">
              <form id="form-agregar-departamento" class="row g-3" action="gestion.php#departamentos" method="POST">
                <div class="col-5">
                  <label for="nombreDepartamento" class="form-label">Nombre del Departamento <b>*</b></label>
                  <input
                    type="text"
                    class="form-control"
                    id="nombreDepartamento"
                    name="nombreDepartamento"
                    value="<?php
                            if ($departamento_editar) {
                              $nombre = $departamento_editar->getNombre();
                              $nombre_departamento = strstr($nombre, '_') ? substr(strstr($nombre, '_'), 1) : $nombre;
                            }
                            echo $departamento_editar ? $nombre_departamento : ''
                            ?>"
                    <?php echo $departamento_editar ? 'readonly' : '' ?>
                    required />
                </div>
                <div class="col-5">
                  <label for="dniDirector" class="form-label">DNI del Director</label>
                  <input
                    type="text"
                    class="form-control"
                    id="dniDirector"
                    value="<?php echo $departamento_editar ? $departamento_editar->getDirectorAcargo()->getDni() : '' ?>"
                    name="dniDirector" />

                </div>
                <div class="col-2 col-relative">
                  <button type="submit" name="accion" value="agregarDepartamento" class="btn custom-color button-absolute">
                    <?php echo !is_null($departamento_editar) ? 'Guardar' : 'Agregar' ?>
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
                          <td><?php echo htmlspecialchars($departamento[0]->getDirectorAcargo() ? $departamento[0]->getDirectorAcargo()->getNombreApellido() : ''); ?></td>
                          <td><?php echo htmlspecialchars($departamento[0]->getDirectorAcargo() ? $departamento[0]->getDirectorAcargo()->getDni() : ''); ?></td>
                          <td>
                            <a href="gestion.php?departamento=<?php echo htmlspecialchars($nombre_empresa . '_' . $departamento[1]); ?>#departamentos" class="btn btn-sm btn-primary">Editar</a>
                            <button class="btn btn-sm btn-danger confirmar-borrar" data-tipo="departamento" data-nombre="<?php echo $nombre_empresa . '_' . $departamento[1]; ?>">
                              Borrar
                            </button>
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
            <button type="submit" class="btn custom-color w-100">
              Enviar
            </button>
          </form>
        </div>

        <!-- Modal para confirmar borrado de usuario -->
        <div
          class="modal fade"
          id="deleteModal"
          tabindex="-1"
          aria-labelledby="deleteModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                  Borrar Usuario
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
                    <p>¿Está seguro que desea eliminar <span id="tipoBorrar"></span>: <b id="nombreBorrar"></b>?</p>
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
                <button type="button" class="btn btn-custom" id="btnConfirmarBorrar">Confirmar</button>
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
  <script src="../js/gestion.js"></script>
</body>

</html>