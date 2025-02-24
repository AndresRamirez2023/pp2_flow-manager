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
    href="../../assets/dist/css/bootstrap.min.css"
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
                title="El domicilio debe contener entre 5 y 100 caracteres, incluyendo letras, números, espacios, y símbolos como ',' o '-'."
                required />
            </div>

            <div class="col-md-6">
              <label for="telefono" class="form-label">Número de Teléfono</label>
              <input
                type="tel"
                class="form-control"
                id="telefono"
                name="telefono"
                pattern="^\+?\d{7,15}$"
                title="El teléfono debe contener entre 7 y 15 dígitos, opcionalmente iniciando con '+'."
                required />
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
                title="El usuario debe ser mayor de edad."
                required />
            </div>

            <div class="col-md-6">
              <label for="empresa" class="form-label">Empresa</label>
              <input
                type="text"
                class="form-control"
                id="empresa"
                name="empresa"
                required />
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
              <label for="password" class="form-label">Contraseña temporal *</label>
              <input
                type="password"
                class="form-control"
                id="clave"
                name="clave"
                pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$"
                title="La contraseña debe tener al menos 8 caracteres, incluyendo una letra y un número."
                required />
            </div>

            <input
              type="hidden"
              class="form-control"
              id="departamento"
              name="departamento" />

            <div class="col-md-12">
              <button type="submit" class="btn btn-primary w-100">Agregar Usuario</button>
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
    </div>
    </main>
  </div>
  </div>
  <!-- Script JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tipoEnvioRadios = document.querySelectorAll('input[name="tipoEnvio"]');
      const seccionAviso = document.getElementById('seccion-aviso');
      const seccionDocumento = document.getElementById('seccion-documento');

      // Función para mostrar la sección correspondiente
      function actualizarSeccion() {
        const tipoEnvioSeleccionado = document.querySelector('input[name="tipoEnvio"]:checked').value;

        if (tipoEnvioSeleccionado === 'aviso') {
          seccionAviso.classList.remove('d-none');
          seccionDocumento.classList.add('d-none');
        } else if (tipoEnvioSeleccionado === 'documento') {
          seccionAviso.classList.add('d-none');
          seccionDocumento.classList.remove('d-none');
        }
      }

      // Inicializa las secciones basadas en la selección por defecto
      actualizarSeccion();

      // Actualiza la sección cuando se cambie la opción
      tipoEnvioRadios.forEach(radio => {
        radio.addEventListener('change', actualizarSeccion);
      });
    });
  </script>

  <!-- Bootstrap 5 JS -->
  <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/profile-menu.js"></script>
  <script src="../js/gestion.js"></script>
</body>

</html>