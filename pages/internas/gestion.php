<?php
require_once '../../classes/Usuario.php';
require_once __DIR__ . '/../../repositories/Repositorio_Departamento.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

// Deserializar el usuario guardado en la sesión
$usuario = unserialize($_SESSION['usuario']);

// Verificar si el usuario es de tipo RRHH
if (!$usuario->esRRHH()) {
  // Si no es RRHH, redirigir a una página de acceso denegado o al login
  header("Location: acceso_denegado.php");
  exit;
}
$objetoClase = new Repositorio_Departamento();
$listaDepartamento = $objetoClase->ObtenerListaDepartamento();
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

        <!-- Contenedor principal -->
        <div class="container my-4 bg-white rounded shadow-sm">
          <h2>Gestión de Departamentos</h2>

          <!-- Fila con dos columnas -->
          <div class="row">
            <!-- Columna izquierda: Agregar departamento -->
            <div class="col-md-6">
              <h4>Agregar Departamento</h4>
              <form id="form-agregar-departamento" class="row g-3" action="../../controllers/Controlador_Departamento.php" method="POST">
                <div class="col-md-12">
                  <label for="nombreDepartamento" class="form-label">Nombre del Departamento</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nombreDepartamento"
                    name="nombreDepartamento"
                    required />
                </div>
                <div class="col-md-12">
                  <label for="dniDirector" class="form-label">DNI del Director</label>
                  <input
                    type="text"
                    class="form-control"
                    id="dniDirector"
                    name="dniDirector"
                    required />
                </div>
                <input type="hidden" name="accion" value="agregar">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary w-100">
                    Agregar Departamento
                  </button>
                </div>
              </form>
            </div>

            <!-- Columna derecha: Modificar departamentos -->
            <!-- Columna derecha: Modificar departamentos -->
            <div class="col-md-6">
              <h4>Modificar Departamentos Existentes</h4>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Nombre del Departamento</th>
                      <th>Nombre del Director</th>
                      <th>DNI del Director</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $listaDepartamento = $listaDepartamento ?? [];

                    if (!empty($listaDepartamento)) {
                      foreach ($listaDepartamento as $lista) {
                        echo "<tr>";
                        echo "<td>{$lista['nombreDepartamento']}</td>";
                        echo "<td>{$lista['nombreDirector']}</td>";
                        echo "<td>{$lista['dniDirector']}</td>";

                        // Botones de acción
                        echo "<td>
                            <button type='button' class='btn btn-warning btn-sm' 
                                onclick=\"mostrarFormularioEditar('{$lista['nombreDepartamento']}','{$lista['dniDirector']}')\">Editar</button>
                            <button type='button' class='btn btn-danger btn-sm' 
                                onclick=\"eliminarDepartamento('{$lista['nombreDepartamento']}')\">Eliminar</button>
                        </td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='4'>No hay datos disponibles</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Modal para editar departamento -->
            <div class="modal fade" id="modalEditarDepartamento" tabindex="-1" aria-labelledby="modalEditarDepartamentoLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarDepartamentoLabel">Editar Departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body">
                    <form id="formEditarDepartamento" action="../../controllers/Controlador_Departamento.php" method="post">
                      <input type="hidden" name="accion" value="editar">
                      <input type="hidden" name="eliminarDepartamento" value="eliminarDepartamento">

                      <div class="mb-3">
                        <label for="editarNombreDepartamento" class="form-label">Nombre del Departamento</label>
                        <input type="text" class="form-control" id="editarNombreDepartamento" name="nombreDepartamento" required />
                      </div>
                      <div class="mb-3">
                        <label for="dniDirectorActual" class="form-label">DNI del Director</label>
                        <input type="text" class="form-control" id="dniDirectorActual" name="dniDirector" required />
                      </div>
                      <div class="mb-3">
                        <label for="nuevoDniDirector" class="form-label">Nuevo dni director</label>
                        <input type="text" class="form-control" id="nuevoDniDirector" name="nuevoDniDirector" />
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <script>
              // Función para mostrar el formulario con datos prellenados
              function mostrarFormularioEditar(nombreDepartamento, dniDirectorActual) {
                document.getElementById("editarNombreDepartamento").value = nombreDepartamento;
                document.getElementById("dniDirectorActual").value = dniDirectorActual; // Guardamos el DNI viejo

                // Mostrar el modal
                var modalEditar = new bootstrap.Modal(document.getElementById('modalEditarDepartamento'));
                modalEditar.show();
              }


              function eliminarDepartamento(nombreDepartamento) {
                if (confirm("¿Estás seguro de que deseas eliminar el departamento " + nombreDepartamento + "?")) {
                  fetch("../../controllers/Controlador_Departamento.php", {
                      method: "POST",
                      headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                      },
                      body: "accion=eliminar&nombreDepartamento=" + encodeURIComponent(nombreDepartamento)
                    })
                    .then(response => response.json()) // Espera una respuesta JSON del servidor
                    .then(data => {
                      if (data.success) {
                        alert("Departamento eliminado correctamente.");
                        location.reload(); // Recargar la página para reflejar cambios
                      } else {
                        alert("Error al eliminar el departamento: " + data.error);
                      }
                    })
                    .catch(error => console.error("Error:", error));
                }
              }
            </script>

            <!-- Sección para enviar avisos o documentación -->
            <div>
              <div class="container my-4 bg-white rounded shadow-sm">
                <h2>Enviar Aviso o Documentación</h2>
                <form id="form-enviar-aviso-doc" action="../../controllers/Controlador_aviso_documento.php" method="POST" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoEnvio" value="aviso" checked id="tipoEnvioAviso">
                        <label class="form-check-label" for="tipoEnvioAviso">
                          Aviso
                        </label>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoEnvio" value="documento" id="tipoEnvioDocumento">
                        <label class="form-check-label" for="tipoEnvioDocumento">
                          Documento
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Sección de aviso -->
                  <div id="seccion-aviso">
                    <div class="row mb-3">
                      <label for="usuarioAviso" class="col-sm-2 col-form-label">Usuario:</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="usuarioAviso" name="usuarioAviso">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="mensajeAviso" class="col-sm-2 col-form-label">Mensaje:</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="mensajeAviso" name="mensajeAviso"></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- Sección de documento -->
                  <div id="seccion-documento" class="d-none">
                    <div class="row mb-3">
                      <label for="usuarioDocumento" class="col-sm-2 col-form-label">Usuario:</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="usuarioDocumento" name="usuarioDocumento">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="mensajeDocumento" class="col-sm-2 col-form-label">Mensaje:</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" id="mensajeDocumento" name="mensajeDocumento"></textarea>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="archivoDocumento" class="col-sm-2 col-form-label">Archivo:</label>
                      <div class="col-sm-10">
                        <input type="file" class="form-control" id="archivoDocumento" name="archivoDocumento">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="requiereFirma" class="col-sm-2 col-form-label">Requiere firma:</label>
                      <div class="col-sm-10">
                        <input type="checkbox" class="form-check-input" id="requiereFirma" name="requiereFirma">
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col text-center">
                      <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                  </div>
                </form>



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