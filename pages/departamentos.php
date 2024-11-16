<!DOCTYPE html>
<!-- Coding by CodingLab | www.codinglabweb.com -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/dist/css/style-home.css">
    <link rel="stylesheet" href="../assets/dist/css/style.css">
    <link href="../bootstrap.min.css" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
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

    <nav>

    </nav>

    <section class="home">
        <div class="text">Dashboard Sidebar</div>

        <!-- Nueva sección con dos columnas -->
        <div class="container mt-5">
            <div class="row">
                <!-- Columna de Integrantes -->
                <div class="col-lg-6">
                    <div class="p-4 bg-light rounded shadow-sm">
                        <h3 class="mb-4">Integrantes</h3>
                        <p><strong>Director:</strong> Juan Reyes</p>

                        <!-- Formulario de añadir empleado -->
                        <form action="agregar_empleado.php" method="POST" class="mb-4">
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI del empleado</label>
                                <input type="text" name="dni" id="dni" class="form-control" placeholder="DNI del empleado" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del empleado</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del empleado" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Añadir Empleado</button>
                        </form>

                        <!-- Lista de integrantes -->
                        <ul class="list-unstyled">
                            <!-- Aquí se listan los empleados dinámicamente desde PHP -->
                            <?php foreach ($departamentos as $empleado): ?>
                                <li><?php echo htmlspecialchars($empleado->getNombre()); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Columna de Próximos eventos -->
                <div class="col-lg-6">
                    <div class="p-4 bg-light rounded shadow-sm">
                        <h3 class="mb-4">Próximos eventos</h3>
                        
                        <!-- Formulario para agregar evento -->
                        <form action="agregar_evento.php" method="POST" class="mb-4">
                            <div class="mb-3">
                                <label for="nombre_evento" class="form-label">Nombre del Evento</label>
                                <input type="text" name="nombre_evento" id="nombre_evento" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_evento" class="form-label">Fecha del Evento</label>
                                <input type="date" name="fecha_evento" id="fecha_evento" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Añadir Evento</button>
                        </form>

                        <!-- Lista de eventos disponibles -->
                        <h4>Eventos Disponibles</h4>
                        <ul class="list-unstyled">
                            <?php
                            require_once 'Repositorio_Departamento.php';
                            $repositorio = new Repositorio_Departamento();
                            $eventos = $repositorio->obtener_eventos_departamento(1); // ID del departamento

                            if ($eventos) {
                                foreach ($eventos as $evento) {
                                    echo "<li>" . htmlspecialchars($evento['fecha_evento']) . ": " . htmlspecialchars($evento['nombre_evento']) . "</li>";
                                }
                            } else {
                                echo "<li>No hay eventos programados</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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