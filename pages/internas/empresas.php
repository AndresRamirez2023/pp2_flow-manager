<?php
require_once '../../controllers/Controlador_Empresa.php';
require_once '../../controllers/Controlador_Usuario.php';

session_start();

if (!isset($_SESSION['super_user'])) {
    header('Location: loginInterno.php');
    exit();
}
$superUser = unserialize($_SESSION['super_user']);

$ce = new Controlador_Empresa();
$empresas = $ce->get_all();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Empresas Registradas &#65381; Flow Manager</title>
    <link rel="stylesheet" href="../../assets/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
    <div class="container my-4 bg-white rounded shadow-sm p-4">
        <h1>Empresas Registradas</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario Principal</th>
                    <th>Contraseña</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empresas as $empresa): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($empresa['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($empresa['usuario_principal']) . ' (' . htmlspecialchars($empresa['dni']) . ')'; ?></td>
                        <td>
                            <span class="password-field">••••••••</span>
                            <button class="btn btn-sm btn-outline-secondary toggle-password" data-password="<?php echo htmlspecialchars($empresa['password']); ?>">Ver</button>
                        </td>
                        <td>
                            <a href="agregarUsuario.php?empresa_id=<?php echo $empresa['id']; ?>" class="btn btn-primary btn-sm">Agregar Usuario</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const passwordField = this.previousElementSibling;
                if (passwordField.textContent === '••••••••') {
                    let enteredPassword = prompt('Ingrese la contraseña del super usuario:');
                    if (enteredPassword === '<?php echo $superUser->getPassword(); ?>') {
                        passwordField.textContent = this.getAttribute('data-password');
                    } else {
                        alert('Contraseña incorrecta.');
                    }
                } else {
                    passwordField.textContent = '••••••••';
                }
            });
        });
    </script>
</body>
</html>
