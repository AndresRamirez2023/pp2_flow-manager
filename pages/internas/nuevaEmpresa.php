<?php
require_once '../../controllers/Controlador_Empresa.php';
require_once '../../classes/Empresa.php';
// require_once '../../controllers/controlador_usuario.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_SESSION['mensaje_mostrado'])) {
    unset($_SESSION['empresaCreada']);
    unset($_SESSION['nombreEmpresa']);
    $_SESSION['mensaje_mostrado'] = true;
}

if (!isset($_SESSION['super_user'])) {
    header('Location: loginInterno.php');
    exit();
}
$superUser = unserialize($_SESSION['super_user']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crearEmpresa') {
    $ce = new Controlador_Empresa();
    $nombreEmpresa = trim($_POST['nombreEmpresa']);

    // Validar si la empresa ya existe
    if ($ce->get_by_name($nombreEmpresa) !== null) {
        $_SESSION['mensaje'] = "La empresa ya existe en el sistema.";
        $_SESSION['mensaje_tipo'] = "danger";
        header('Location: nuevaEmpresa.php');
        exit();
    }

    // Procesar archivos
    $nombreEmpresaLimpio = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreEmpresa);
    $directorioBase = "../../uploads/$nombreEmpresaLimpio/";
    $directorioImagenes = $directorioBase . "images/";
    $directorioArchivos = $directorioBase . "files/";

    // Crear directorios si no existen
    if (!file_exists($directorioImagenes)) {
        mkdir($directorioImagenes, 0777, true);
    }
    if (!file_exists($directorioArchivos)) {
        mkdir($directorioArchivos, 0777, true);
    }

    // Función para obtener la extensión del archivo
    function obtenerExtension($nombreArchivo)
    {
        return strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    }

    // Guardar imágenes
    $logoPath = null;
    if (!empty($_FILES['logoEmpresa']['name'])) {
        $extensionLogo = obtenerExtension($_FILES['logoEmpresa']['name']);
        $logoPath = $directorioImagenes . "logo." . $extensionLogo;
        move_uploaded_file($_FILES['logoEmpresa']['tmp_name'], $logoPath);
    }

    $fondoPath = null;
    if (!empty($_FILES['fondoEmpresa']['name'])) {
        $extensionFondo = obtenerExtension($_FILES['fondoEmpresa']['name']);
        $fondoPath = $directorioImagenes . "fondo." . $extensionFondo;
        move_uploaded_file($_FILES['fondoEmpresa']['tmp_name'], $fondoPath);
    }

    // Crear empresa
    $empresa = new Empresa($nombreEmpresa, $fondoPath, $logoPath);
    $result = $ce->create($empresa);

    if ($result) {
        $_SESSION['empresaCreada'] = true;
        $_SESSION['nombreEmpresa'] = $nombreEmpresa;
        $_SESSION['mensaje'] = "Empresa creada correctamente.";
        $_SESSION['mensaje_tipo'] = "info";
    } else {
        $_SESSION['mensaje'] = "Error al crear la empresa.";
        $_SESSION['mensaje_tipo'] = "danger";
    }
    header('Location: nuevaEmpresa.php');
    exit();
} elseif (isset($_POST['accion']) && $_POST['accion'] === 'crearUsuario') {
    if (!isset($_SESSION['empresaCreada']) || !$_SESSION['empresaCreada']) {
        $_SESSION['mensaje'] = "Debe crear una empresa antes de registrar un usuario.";
        $_SESSION['mensaje_tipo'] = "danger";
        header('Location: nuevaEmpresa.php');
        exit();
    }
}

// Control de estado de creación
$empresaCreada = isset($_SESSION['empresaCreada']) ? $_SESSION['empresaCreada'] : false;
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agregar Empresa &#65381; Flow Manager</title>
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

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['mensaje_tipo']; ?> mt-3">
                            <?php echo $_SESSION['mensaje']; ?>
                        </div>
                        <?php
                        unset($_SESSION['mensaje']);
                        unset($_SESSION['mensaje_tipo']);
                        unset($_SESSION['mensaje_mostrado']);
                        ?>
                    <?php endif; ?>

                    <form id="empresa-usuario-form" class="mt-4" action="nuevaEmpresa.php" method="POST"
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