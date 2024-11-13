<?php
/*
session_start();
require_once 'Usuario.php'; // Incluye la clase Usuario para manejar los datos

 //Verificar si el usuario tiene permisos para agregar usuarios de RRHH
if ($_SESSION['tipo_usuario'] != 'RRHH') {
    header('Location: error.php'); // Redirigir a una página de error si no es RRHH
    exit();
}

 //Manejo de la lógica para agregar un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $password_temporal = bin2hex(random_bytes(8)); // Genera una contraseña temporal aleatoria

     //Aquí deberia insertar los datos en la base de datos
     //EJEMPLOOOOO con una clase de usuario que guarda en la DB
    $usuario = new Usuario($nombre, $dni, $email, $tipo_usuario, $password_temporal);
    if ($usuario->guardar()) {
        echo 'Usuario agregado correctamente.';
        header('Location: listado_usuarios.php'); // Redirige a la página de listado de usuarios
        exit();
    } else {
        echo 'Error al agregar el usuario.';
    }
} */
?>

<!DOCTYPE html>
<!-- Coding by CodingLab | www.codinglabweb.com -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="../assets/dist/css/style-home.css">
    <link rel="stylesheet" href="../assets/dist/css/style.css">
    <link href="../bootstrap.min.css" rel="stylesheet">

    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <!--<title>Dashboard Sidebar Menu</title>-->
</head>

<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="../assets/brand/FlowManager.jpg" alt="">
                </span>

                <div class="text logo-text">
                    <span class="name">Codinglab</span>
                    <span class="profession">Web developer</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search...">
                </li>

                <li class="nav-link">
                    <a href="#">
                        <i class='bx bx-home-alt icon'></i>
                        <span class="text nav-text">Home</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#">
                        <i class='bx bx-folder icon'></i>
                        <span class="text nav-text">Documentos</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#">
                        <i class='bx bx-bell icon'></i>
                        <span class="text nav-text">Notificaiones</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="#">
                        <i class='bx bx-calendar icon'></i>
                        <span class="text nav-text">Calendario</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="mensajeria.php">
                        <i class='bx bx-chat icon'></i>
                        <span class="text nav-text">Mensajes</span>
                    </a>
                </li>


            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="#">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Salir</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>

            </div>
        </div>
    </nav>
    <section class="home">
        <div class="text">Dashboard Sidebar</div>
        <div class="container text-start">
            <h3>Agregar Usuario</h3>

            <!-- Sección para agregar usuario -->
            <div class="row">
                <div class="col-lg-6">
                    <form action="procesar_agregar_usuario.php" method="POST">
                        <div class="form-group">
                            <label for="nombreApellido">Nombre y apellido </label>
                            <input type="text" id="nombre" name="nombreApellido" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="domicilio">Domicilio</label>
                            <input type="text" id="domicilio" name="domicilio" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo_usuario">Tipo de Usuario</label>
                            <select id="tipo_usuario" name="tipo_usuario" class="form-control" required>
                                <option value="admin">Administrador</option>
                                <option value="usuario">Usuario</option>
                            </select>
                        </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="dni">DNI*</label>
                        <input type="text" id="dni" name="dni" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="numtelefono">Numero de telefono</label>
                        <input type="text" id="numtelefono" name="numtelefono" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="fechanac">Fecha de Nacimiento</label>
                        <input type="date" id="fechanac" name="fechanac" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña temporal</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary mt-3">Agregar Usuario</button>
            </div>
            </form>

                  <!-- Sección para agregar departamento -->
        <div class="container text-start mt-4">
            <h3>Agregar Departamento</h3>

            <form action="procesar_agregar_departamento.php" method="POST">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="nombreDepartamento">Nombre departamento </label>
                            <input type="text" id="nombreDepartamento" name="nombreDepartamento" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="dniDirector">DNI del director</label>
                            <input type="text" id="dniDirector" name="dniDirector" class="form-control" required>
                        </div>
                    </div>
                    
                <button type="submit" class="btn btn-primary mt-3">Agregar Departamento</button>
                </div>

            </form>
        </div>
    </section>
    <script>
        const body = document.querySelector('body'),
            sidebar = body.querySelector('nav'),
            toggle = body.querySelector(".toggle"),
            searchBtn = body.querySelector(".search-box"),
            modeSwitch = body.querySelector(".toggle-switch"),
            modeText = body.querySelector(".mode-text");

        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        })

        searchBtn.addEventListener("click", () => {
            sidebar.classList.remove("close");
        })

        modeSwitch.addEventListener("click", () => {
            body.classList.toggle("dark");

            if (body.classList.contains("dark")) {
                modeText.innerText = "Light mode";
            } else {
                modeText.innerText = "Dark mode";
            }
        });
    </script>

</body>

</html>