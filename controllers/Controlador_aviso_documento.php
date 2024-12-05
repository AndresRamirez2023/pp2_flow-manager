<?php
require_once '../classes/Usuario.php';
require_once '../repositories/Repositorio_AvisoDocumento.php';
require_once '../classes/AvisoDocumento.php';
require_once '../classes/Archivo.php';  // Incluir la clase Archivo

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Redirigir al login
    header('Location: ../../index.php');
    exit;
}

$repositorio = new Repositorio_AvisoDocumento();

// Obtener datos del formulario
$tipoEnvio = $_POST['tipoEnvio'];

// Inicializar variables para manejar datos del aviso/documento
$tituloAviso = $cuerpoAviso = $departamentosAviso = null;
$usuarioDocumento = $archivoDocumento = $mensajeDocumento = $requiereFirma = null;

if ($tipoEnvio === 'aviso') {
    // Datos específicos para aviso
    $tituloAviso = $_POST['tituloAviso'];
    $cuerpoAviso = $_POST['cuerpoAviso'];
    $departamentosAviso = isset($_POST['departamentosAviso']) ? $_POST['departamentosAviso'] : [];
} elseif ($tipoEnvio === 'documento') {
    // Datos específicos para documento
    $usuarioDocumento = $_POST['usuarioDocumento'];
    $mensajeDocumento = $_POST['mensajeDocumento'];
    $requiereFirma = isset($_POST['requiereFirma']) ? 1 : 0;

    // Manejo del archivo subido
    if (isset($_FILES['archivoDocumento']) && $_FILES['archivoDocumento']['error'] === UPLOAD_ERR_OK) {
        $archivoDocumento = file_get_contents($_FILES['archivoDocumento']['tmp_name']);
        $nombreArchivo = $_FILES['archivoDocumento']['name'];
        $fechaCreacion = date("Y-m-d H:i:s"); // Fecha de creación del archivo
        $usuario = unserialize($_SESSION['usuario']);
        $dniCreador = $usuario->getDni(); // DNI del creador del archivo
        
        // Crear el objeto Archivo con los datos recibidos
        $archivo = new Archivo($nombreArchivo, $archivoDocumento, $fechaCreacion, $dniCreador);
    } else {
        echo "Error al subir el archivo.";
        exit;
    }
} else {
    echo "Tipo de envío no válido.";
    exit;
}

// Crear objeto AvisoDocumento
$usuario = unserialize($_SESSION['usuario']);
$avisoDocumento = new AvisoDocumento();
$avisoDocumento->setTipoEnvio($tipoEnvio);
$avisoDocumento->setUsuarioId($usuario->getDni());

if ($tipoEnvio === 'aviso') {
    $avisoDocumento->setTitulo($tituloAviso);
    $avisoDocumento->setCuerpo($cuerpoAviso);
    $avisoDocumento->setDepartamentos($departamentosAviso);
} elseif ($tipoEnvio === 'documento') {
    $avisoDocumento->setUsuarioDocumento($usuarioDocumento);
    $avisoDocumento->setArchivo($archivo); // Establecer el objeto Archivo en AvisoDocumento
    $avisoDocumento->setMensaje($mensajeDocumento);
    $avisoDocumento->setRequiereFirma($requiereFirma);
}

// Guardar en la base de datos
if ($repositorio->guardar($avisoDocumento)) {
    echo "Envío realizado correctamente.";
    // Redirigir a una página de confirmación o al panel principal
    header('Location: ../pages/internas/panel.php');
    exit;
} else {
    echo "Error al realizar el envío.";
}
?>
