<?php
require_once '../../../controllers/Controlador_Empresa.php';
require_once '../../../controllers/Controlador_Usuario.php';

session_start();

if (!isset($_SESSION['super_user'])) {
    header('Location: loginInterno.php');
    exit();
}
$superUser = unserialize($_SESSION['super_user']);

$ce = new Controlador_Empresa();
$empresas = $ce->get_all();

unset($_SESSION['mensaje_mostrado']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Empresas Registradas &#65381; Flow Manager</title>
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
            <main class="vh col-md-12 main-content">
                <!-- Formulario Empresa y Usuario -->
                <div class="container my-4 bg-white rounded shadow-sm p-4 ">
                    <h1>Empresas Registradas</h1>
                    <a href="nuevaEmpresa.php" class="btn btn-secondary btn-sm">Agregar Empresa</a>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario Principal</th>
                                <th>DNI</th>
                                <th>Correo electrónico</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($empresas) === 0) {
                                echo '<tr>
                            <th colspan="6" class="text-center"><h3>No se agregaron empresas al sistema</h3></th>
                            </tr>';
                            } else {
                                foreach ($empresas as $empresa): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($empresa->getNombre()); ?></td>
                                        <td><?php echo $empresa->getUsuarioPrincipal()->getNombreApellido() ? $empresa->getUsuarioPrincipal()->getNombreApellido() : '<b>Sin asignar</b>'; ?></td>
                                        <td><?php echo htmlspecialchars($empresa->getUsuarioPrincipal()->getDni()); ?></td>
                                        <td><?php echo htmlspecialchars($empresa->getUsuarioPrincipal()->getCorreoElectronico()); ?></td>
                                        <td>
                                            <a href="nuevaEmpresa.php?nombreEmpresa=<?php echo $empresa->getNombre(); ?>" class="btn btn-primary btn-sm">Agregar Usuario</a>
                                        </td>
                                    </tr>
                            <?php endforeach;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; 2025 Flow Manager. Todos los derechos reservados.</p>
    </footer>

    <script src="../../../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/profile-menu.js"></script>
</body>

</html>