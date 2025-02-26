<?php
require_once '../../../controllers/Controlador_Empresa.php';
require_once '../../../controllers/Controlador_Usuario.php';
require_once '../../../controllers/Controlador_Departamento.php';
require_once '../../../classes/Empresa.php';
require_once '../../../classes/Usuario.php';

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

$nombreEmpresa = null;
if (isset($_GET['nombreEmpresa'])) {
    $nombreEmpresa = $_GET['nombreEmpresa'];

    $ce = new Controlador_Empresa();
    $e = $ce->get_by_name($nombreEmpresa);

    if ($e !== null) {
        $_SESSION['empresaCreada'] = true;
        $_SESSION['nombreEmpresa'] = $nombreEmpresa;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crearEmpresa') {
    $ce = new Controlador_Empresa();
    $nombreEmpresa = trim($_POST['nombreEmpresa']);

    // Validar si la empresa ya existe
    if ($ce->get_by_name($nombreEmpresa) !== null) {
        $_SESSION['mensaje'] = "La empresa <b>ya se encuentra registrada</b> en el sistema. Verifique el nombre y/o las empresas creadas anteriormente.";
        $_SESSION['mensaje_tipo'] = "danger";
        header('Location: nuevaEmpresa.php');
        exit();
    }

    // Procesar archivos
    $nombreEmpresaLimpio = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreEmpresa);
    $directorioBase = "../../../uploads/$nombreEmpresaLimpio/";
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
    $empresa = new Empresa($nombreEmpresa, null, $fondoPath, $logoPath);
    $result = $ce->create($empresa);

    if ($result) {
        $_SESSION['empresaCreada'] = true;
        $_SESSION['nombreEmpresa'] = $nombreEmpresa;
        $_SESSION['mensaje'] = "Empresa <b>creada correctamente</b>. Ya puede crear el <b>primer usuario</b> asociado a la empresa para entregar al cliente.";
        $_SESSION['mensaje_tipo'] = "info";
    } else {
        $_SESSION['mensaje'] = "<b>Error</b> al crear la empresa. Verifique los datos, si el problema persiste <b>contacte a un administrador</b>.";
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

    $cu = new Controlador_Usuario();
    $cd = new Controlador_Departamento();
    $nombreEmpresa = $_SESSION['nombreEmpresa'];

    // Datos del usuario
    $dni = trim($_POST['dni']);
    $nombreApellido = trim($_POST['nombre']) . ' ' . trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $tipoUsuario = "RRHH";
    $clave_generada = preg_replace('/[^A-Za-z0-9-]/', '.', $nombreEmpresa) . "123";

    while (strlen($clave_generada) < 8) {
        $clave_generada .= strlen($clave_generada) - 2;
    }

    echo "<p>Clave generada: <strong>" . htmlspecialchars($clave_generada, ENT_QUOTES, 'UTF-8') . "</strong></p>";

    if (empty($dni) || empty($nombreApellido) || empty($email)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios. Revise los datos ingresados.";
        $_SESSION['mensaje_tipo'] = "danger";
        header('Location: nuevaEmpresa.php');
        exit();
    }

    if ($cu->get_by_dni($dni) !== null) {
        $_SESSION['mensaje'] = "El usuario <b>ya se encuentra registrado</b> en el sistema. Revise los datos ingresados y/o los usuarios creados anteriormente.";
        $_SESSION['mensaje_tipo'] = "danger";
        header('Location: nuevaEmpresa.php');
        exit();
    }

    // Crear objeto Departamento
    $empresa = new Empresa($nombreEmpresa);
    $departamento = new Departamento($nombreEmpresa  . "_Recursos Humanos", null, $empresa);

    $result = $cd->create($departamento);

    // Objeto Usuario
    $usuario = new Usuario(
        $dni,
        $email,
        $tipoUsuario,
        $departamento,
        $nombreApellido
    );

    $result = $cu->save($usuario, $clave_generada);

    if ($result) {
        $_SESSION['empresaCreada'] = false;
        $_SESSION['nombreEmpresa'] = null;
        $_SESSION['mensaje'] = "Usuario <b>creado correctamente</b> con la clave <b>" . $clave_generada . "</b> . Ya puede compartir la <b>información inicial</b> con el cliente.";
        $_SESSION['mensaje_tipo'] = "info";
    } else {
        if (!isset($_SESSION['mensaje']) && !isset($_SESSION['mensaje_tipo'])) {
            $_SESSION['mensaje'] = "<b>Error</b> al crear el usuario. Verifique los datos, si el problema persiste <b>contacte a un administrador</b>.";
            $_SESSION['mensaje_tipo'] = "danger";
        }
    }

    header('Location: nuevaEmpresa.php');
    exit();
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
    <link rel="icon" href="../../img/Icon - FlowManager.png">

    <!-- Bootstrap 5 CSS -->
    <link
        href="../../../assets/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/styles.css" />
    <link rel="stylesheet" href="../../css/panel.css" />

</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="empresas.php">
                    <img id="logo" src="../../img/Logo - FlowManager.svg" alt="Logo Empresa" />
                </a>

                <!-- Navegación -->
                <div class="navbar-nav ms-auto">
                    <!-- menú flotante -->
                    <div class="profile-container position-relative">
                        <div class="nav-profile me-4" id="profileMenu" role="button">
                            <img src="../../img/empleador.jpg" alt="Foto de perfil" class="profile-pic-img" />
                        </div>

                        <!-- Menú desplegable -->
                        <div class="dropdown-menu" id="profileDropdown">
                            <a href="../../php/logoutInterno.php" class="dropdown-item">
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
                                <?php echo $empresaCreada ? 'disabled' : ''; ?>>
                        </div>

                        <div class="mb-3">
                            <label for="fondoEmpresa" class="form-label">Fondo de inicio (JPG, PNG, SVG)</label>
                            <input type="file" class="form-control" id="fondoEmpresa" name="fondoEmpresa"
                                accept="image/*" <?php echo $empresaCreada ? 'disabled' : ''; ?>>
                        </div>

                        <button type="submit" class="btn btn-primary" name="accion" value="crearEmpresa" <?php echo $empresaCreada ? 'disabled' : ''; ?>>Crear Empresa</button>

                        <h2 class="mt-4">Datos del Usuario primario</h2>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre/s <b>*</b></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" <?php echo !$empresaCreada ? 'disabled' : ''; ?> pattern="^[A-Za-zÀ-ÿ\s]{2,50}$"
                                    title="El nombre debe contener solo letras y espacios, entre 2 y 50 caracteres."
                                    required />
                            </div>

                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido/s <b>*</b></label>
                                <input type="text" class="form-control" id="apellido" name="apellido" <?php echo !$empresaCreada ? 'disabled' : ''; ?> pattern="^[A-Za-zÀ-ÿ\s]{2,50}$"
                                    title="El apellido debe contener solo letras y espacios, entre 2 y 50 caracteres."
                                    required />
                            </div>

                            <div class="col-md-6">
                                <label for="dni" class="form-label">DNI <b>*</b></label>
                                <input type="text" class="form-control" id="dni" name="dni" <?php echo !$empresaCreada ? 'disabled' : ''; ?> maxlength="8" pattern="\d{8}"
                                    title="El DNI debe tener exactamente 8 dígitos numéricos." required />
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <b>*</b></label>
                                <input type="email" class="form-control" id="email" name="email" <?php echo !$empresaCreada ? 'disabled' : ''; ?> required />
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary" name="accion"
                            value="crearUsuario" id="crearUsuarioBtn" <?php echo !$empresaCreada ? 'disabled' : ''; ?>>Crear Usuario</button>
                    </form>
                </div>
        </div>
        </main>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; 2025 Flow Manager. Todos los derechos reservados.</p>
    </footer>

    <script src="../../../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/profile-menu.js"></script>
</body>

</html>