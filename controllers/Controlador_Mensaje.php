<?php


require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../repositories/Repositorio_Mensaje.php';
require_once __DIR__ . '/../classes/Mensaje.php';
require_once __DIR__ . '/../classes/archivo.php';
require_once __DIR__ . '/../repositories/Repositorio_Archivo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // **Registro de depuración para verificar los datos recibidos**
    error_log("Datos recibidos: " . print_r($_POST, true));
    error_log("Archivo recibido: " . print_r($_FILES, true));

    $FechaHoraMensaje = date('Y-m-d H:i:s');
    $TituloMensaje = $_POST['tituloMensaje'] ?? null;
    $DniRemitente = $_POST['DniSolicitante'] ?? null;
    $TipoMensaje = $_POST['tipoMensaje'] ?? null;
    $CuerpoMensaje = $_POST['mensaje'] ?? null;
    $DniReceptor = $_POST['destinatario'] ?? null;

    $archivo_subido = false;

    $repositorio = new Repositorio_Mensaje();

    $resultado = $repositorio->redactar_mensaje(
        $FechaHoraMensaje, 
        $TituloMensaje, 
        $DniRemitente, 
        $TipoMensaje, 
        $CuerpoMensaje, 
        $DniReceptor, 
        $archivo=null
    );

    // **Verificar si el mensaje se guardó correctamente**
    if ($resultado) {
        error_log("Mensaje guardado correctamente.");
    } else {
        error_log("Error al guardar el mensaje en la base de datos.");
    }

    // 2. Si el mensaje es de tipo "Documentación", procesar el archivo
    if ($TipoMensaje === "Documentacion" && isset($_FILES['archivo'])) {
        $archivo = $_FILES['archivo'];
    
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            $nombre_archivo = basename($archivo['name']);
            $contenido_archivo = file_get_contents($archivo['tmp_name']);  // Asegúrate de que este archivo se está cargando correctamente
    
            // Verifica que el archivo se cargó correctamente
            if ($contenido_archivo === false) {
                error_log("Error al leer el archivo.");
            }
    
            // Intentar guardar el archivo en la base de datos
            $archivo_subido = $repositorio->guardarArchivo($nombre_archivo, $FechaHoraMensaje, $contenido_archivo, $DniRemitente);
    
            // Verificar si el archivo se guardó correctamente
            if ($archivo_subido) {
                error_log("Archivo guardado correctamente en la base de datos.");
            } else {
                error_log("Error al guardar el archivo en la base de datos.");
            }
        } else {
            error_log("Error en la subida del archivo: " . $_FILES['archivo']['error']);
        }
    }
    

    if ($archivo_subido || $TipoMensaje !== "Documentacion") {
        echo json_encode(["success" => true, "message" => "Mensaje enviado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al enviar el mensaje."]);
    }
}
