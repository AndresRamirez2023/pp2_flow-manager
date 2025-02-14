<?php
session_start();

if (!isset($_SESSION['super_user'])) {
    header('Location: loginInterno.php');
    exit();
}
$superUser = unserialize($_SESSION['super_user']);

// Control de estado de creación
$empresaCreada = isset($_SESSION['empresaCreada']) ? $_SESSION['empresaCreada'] : false;
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Creación de Empresa &#65381; Flow Manager</title>
    <link rel="icon" href="../img/Icon - FlowManager.png">

    <!-- Bootstrap 5 CSS -->
    <link
        href="../../assets/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="../css/panel.css" />

</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- Logo de la empresa cliente -->
                <a class="navbar-brand" href="nuevaEmpresa.php">
                    <img id="logo" src="../img/Logo - FlowManager.svg" alt="Logo Empresa" />
                </a>

                <!-- Navegación -->
                <div class="navbar-nav ms-auto">
                    <!-- Foto de perfil y menú flotante -->
                    <div class="profile-container position-relative">
                        <div class="nav-profile me-4" id="profileMenu" role="button">
                            <img src="../img/empleador.jpg" alt="Foto de perfil" class="profile-pic-img" />
                        </div>

                        <!-- Menú desplegable -->
                        <div class="dropdown-menu" id="profileDropdown">
                            <a href="logoutInterno.php" class="dropdown-item">
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
                <!-- Formulario Empresa y Usuario -->
                <div class="container my-4 bg-white rounded shadow-sm px-5">
                    <h1>Administrador de Empresas</h1>
                    <form id="empresa-usuario-form" class="mt-4" action="../php/guardar_datos.php" method="POST"
                        enctype="multipart/form-data">
                        <h2>Datos de nueva empresa</h2>
                        <div class="mb-3">
                            <label for="nombreEmpresa" class="form-label">Razón social</label>
                            <input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa" <?php echo $empresaCreada ? 'disabled value="' . $_SESSION['nombreEmpresa'] . '"' : 'required'; ?>>
                        </div>

                        <div class="mb-3">
                            <label for="logoEmpresa" class="form-label">Logo (JPG, PNG, SVG)</label>
                            <input type="file" class="form-control" id="logoEmpresa" name="logoEmpresa" accept="image/*"
                                <?php echo $empresaCreada ? 'disabled' : 'required'; ?>>
                        </div>

                        <div class="mb-3">
                            <label for="fondoEmpresa" class="form-label">Fondo de inicio (JPG, PNG, SVG)</label>
                            <input type="file" class="form-control" id="fondoEmpresa" name="fondoEmpresa"
                                accept="image/*" <?php echo $empresaCreada ? 'disabled' : ''; ?>>
                        </div>

                        <button type="submit" class="btn btn-primary" name="accion" value="crearEmpresa" <?php echo $empresaCreada ? 'disabled' : ''; ?>>Crear Empresa</button>

                        <h2 class="mt-4">Datos del Usuario primario</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombreApellido" class="form-label">Nombre y Apellido<b>*</b></label>
                                <input type="text" class="form-control" id="nombreApellido" name="nombreApellido" <?php echo !$empresaCreada ? 'disabled' : ''; ?> pattern="^[A-Za-zÀ-ÿ\s]{2,50}$"
                                    title="El nombre debe contener solo letras y espacios, entre 2 y 50 caracteres."
                                    required />
                            </div>

                            <div class="col-md-6">
                                <label for="dni" class="form-label">DNI <b>*</b></label>
                                <input type="text" class="form-control" id="dni" name="dni" <?php echo !$empresaCreada ? 'disabled' : ''; ?> maxlength="8" pattern="\d{8}"
                                    title="El DNI debe tener exactamente 8 dígitos numéricos." required />
                            </div>

                            <div class="col-md-6">
                                <label for="domicilio" class="form-label">Domicilio</label>
                                <input type="text" class="form-control" id="domicilio" name="domicilio" <?php echo !$empresaCreada ? 'disabled' : ''; ?> pattern="^[A-Za-z0-9\s,.-]{5,100}$"
                                    title="El domicilio debe contener entre 5 y 100 caracteres, incluyendo letras, números, espacios, y símbolos como ',' o '-'."
                                    required />
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Número de Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" <?php echo !$empresaCreada ? 'disabled' : ''; ?> pattern="^\+?\d{7,15}$"
                                    title="El teléfono debe contener entre 7 y 15 dígitos, opcionalmente iniciando con '+'."
                                    required />
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <b>*</b></label>
                                <input type="email" class="form-control" id="email" name="email" <?php echo !$empresaCreada ? 'disabled' : ''; ?> required />
                            </div>

                            <div class="col-md-6">
                                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"
                                    <?php echo !$empresaCreada ? 'disabled' : ''; ?> max="2006-12-31"
                                    title="El usuario debe ser mayor de edad." required />
                            </div>

                            <div class="col-md-6">
                                <label for="TipoDeUsuario" class="form-label">Tipo de usuario <b>*</b></label>
                                <select name="TipoDeUsuario" id="TipoDeUsuario" class="form-select" <?php echo !$empresaCreada ? 'disabled' : ''; ?> required>
                                    <option value="">Seleccione un tipo de usuario</option>
                                    <option value="RRHH">RRHH</option>
                                    <option value="Directivo">Directivo</option>
                                    <option value="Empleado">Empleado</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña temporal *</label>
                                <input type="password" class="form-control" id="clave" name="clave" <?php echo !$empresaCreada ? 'disabled' : ''; ?>
                                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$"
                                    title="La contraseña debe tener al menos 8 caracteres, incluyendo una letra y un número."
                                    required />
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100 mt-3" name="accion"
                                    value="crearUsuario" id="crearUsuarioBtn" <?php echo !$empresaCreada ? 'disabled' : ''; ?>>Crear Usuario</button>
                            </div>
                        </div>
                    </form>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-info mt-3">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php
                        unset($_SESSION['mensaje']);
                    endif; ?>
                </div>
        </div>
        </main>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; 2025 Flow Manager. Todos los derechos reservados.</p>
    </footer>

    <script src="../../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/profile-menu.js"></script>
</body>

</html>