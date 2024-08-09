<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Pedro Roberti">
    <meta name="generator" content="-">
    <title>Inicio sesion</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">
    <link rel="stylesheet" href="./assets/dist/css/style.css">

    <link href="bootstrap.min.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin w-100 m-auto">
        <form action="paginas/home.php" method="post">
            <h1 class="h3 mb-2 fw-normal">Bienvenido a </h1><br>
            <img src="./assets/brand/FlowManager.jpg" width="300px" alt="">
            <h2 class="h3 mb-3 fw-normal" style="color:gray">Ingrese con su cuenta</h1>

                <?php
                if (isset($_GET['mensaje'])) {
                    echo '<div id="mensaje" class="alert alert-primary text-center">
                    <p>' . $_GET['mensaje'] . '</p></div>';
                }
                ?>

                <div class="form-floating ">
                    <input name="usuario" class="form-control" id="floatingInput" placeholder="Usuario">
                    <label for="floatingInput">Usuario</label>
                </div>
                <div class="form-floating w-100">
                    <input name="clave" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Contraseña</label>
                </div>

                <button class="w-100 btn btn-lg btn-primary"  type="submit">Ingresar</button>
                <a href="create.php">Crear cuenta</a>
                <p class="mt-3 mb-3 text-muted">&copy; FlowManager-Pedro Roberti-Andres Ramirez</p>
        </form>
    </main>



</body>

</html>