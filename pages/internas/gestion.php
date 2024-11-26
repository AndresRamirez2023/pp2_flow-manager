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
  <title>Gestión &#65381; Flow Manager</title>
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
          <h2>Agregar Usuario</h2>
          <form id="form-agregar-usuario" class="row g-3" action="../../controllers/controlador_usuario.php" method="POST">
            <div class="col-md-6">
              <label for="nombreApellido" class="form-label">Nombre <b>*</b></label>
              <input
                type="text"
                class="form-control"
                id="nombreApellido"
                name="nombreApellido"
                required />
            </div>
            <div class="col-md-6">
              <label for="dni" class="form-label">DNI <b>*</b></label>
              <input type="text" class="form-control" id="dni" name="dni" required />
            </div>
            <div class="col-md-6">
              <label for="domicilio" class="form-label">Domicilio</label>
              <input
                type="text"
                class="form-control"
                id="domicilio" name="domicilio"
                required />
            </div>
            <div class="col-md-6">
              <label for="telefono" class="form-label">Número de Teléfono</label>
              <input type="tel" class="form-control" id="telefono" name="telefono" required />
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email <b>*</b></label>
              <input type="email" class="form-control" id="email" name="email" required />
            </div>
            <div class="col-md-6">
              <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
              <input
                type="date"
                class="form-control"
                id="fechaNacimiento" name="fechaNacimiento"
                required />
            </div>
            <div class="col-md-6">
              <label for="tipoUsuario" class="form-label">Tipo de usuario <b>*</b></label>
              <select name="tipoUsuario" id="tipoUsuario" class="form-select">
                <option value="">RRHH</option>
                <option value="">Directivo</option>
                <option value="">Empleado</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="password" class="form-label">Contraseña temporal *</label>
              <input
                type="password"
                class="form-control"
                id="clave"
                name="clave"
                required />
            </div>
            <div class="col-md-6">
              <label for="departamento" class="form-label"></label>
              <input
                type="hidden"
                class="form-control"
                id="departamento"
                name="departamento" />
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary w-100">
                Agregar Usuario
              </button>
            </div>
          </form>
        </div>

        <script>
          function handleSubmit() {
            // Si no hay valor en el campo oculto de departamento, asigna 'NULL'
            if (document.getElementById('departamento').value === "") {
              document.getElementById('departamento').value = null;
            }
          }
        </script>

        <!-- Sección para agregar departamentos -->
        <div class="container my-4 bg-white rounded shadow-sm">
          <h2>Agregar Departamento</h2>
          <form id="form-agregar-departamento" class="row g-3">
            <div class="col-md-6">
              <label for="nombreDepartamento" class="form-label">Nombre del Departamento</label>
              <input
                type="text"
                class="form-control"
                id="nombreDepartamento"
                required />
            </div>
            <div class="col-md-6">
              <label for="dniDirector" class="form-label">DNI del Director</label>
              <input
                type="text"
                class="form-control"
                id="dniDirector"
                required />
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary w-100">
                Agregar Departamento
              </button>
            </div>
          </form>
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
</body>

</html>