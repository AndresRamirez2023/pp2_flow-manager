<?php
require_once '../classes/Usuario.php';
require_once '../repositories/Repositorio_AvisoDocumento.php';
require_once '../classes/AvisoDocumento.php';
require_once '../classes/Archivo.php'; // Incluir la clase Archivo

class Controlador_aviso_documento {
    private $repositorio;
    private $usuario;

    public function __construct() {
        session_start();
        
        if (!isset($_SESSION['usuario'])) {
            header('Location: ../../index.php');
            exit;
        }
        
        $this->usuario = unserialize($_SESSION['usuario']);
        // $this->repositorio = new Repositorio_AvisoDocumento(); ???????????????????????????????????????????
    }

    public function procesarSolicitud() {
        if (!isset($_POST['tipoEnvio'])) {
            echo "Tipo de envío no válido.";
            exit;
        }

        $tipoEnvio = $_POST['tipoEnvio'];
        $avisoDocumento = new AvisoDocumento();
        $avisoDocumento->setTipoEnvio($tipoEnvio);
        $avisoDocumento->setUsuarioId($this->usuario->getDni());

        if ($tipoEnvio === 'aviso') {
            $this->procesarAviso($avisoDocumento);
        } elseif ($tipoEnvio === 'documento') {
            $this->procesarDocumento($avisoDocumento);
        } else {
            echo "Tipo de envío no válido.";
            exit;
        }

        if ($this->repositorio->guardar($avisoDocumento)) {
            header('Location: ../pages/internas/panel.php');
            exit;
        } else {
            echo "Error al realizar el envío.";
        }
    }

    private function procesarAviso(AvisoDocumento $avisoDocumento) {
        $titulo = $_POST['tituloAviso'] ?? '';
        $cuerpo = $_POST['cuerpoAviso'] ?? '';
        $departamentos = $_POST['departamentosAviso'] ?? [];
        
        $avisoDocumento->setTitulo($titulo);
        $avisoDocumento->setCuerpo($cuerpo);
        $avisoDocumento->setDepartamentos($departamentos);
    }

    private function procesarDocumento(AvisoDocumento $avisoDocumento) {
        $usuarioDocumento = $_POST['usuarioDocumento'] ?? '';
        $mensaje = $_POST['mensajeDocumento'] ?? '';
        $requiereFirma = isset($_POST['requiereFirma']) ? 1 : 0;
        
        $archivo = $this->procesarArchivo();
        if (!$archivo) {
            echo "Error al subir el archivo.";
            exit;
        }

        $avisoDocumento->setUsuarioDocumento($usuarioDocumento);
        $avisoDocumento->setMensaje($mensaje);
        $avisoDocumento->setRequiereFirma($requiereFirma);
        $avisoDocumento->setArchivo($archivo);
    }

    private function procesarArchivo() {
        if (!isset($_FILES['archivoDocumento']) || $_FILES['archivoDocumento']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $contenido = file_get_contents($_FILES['archivoDocumento']['tmp_name']);
        $nombre = $_FILES['archivoDocumento']['name'];
        $fechaCreacion = date("Y-m-d H:i:s");
        $dniCreador = $this->usuario->getDni();
        
        return new Archivo($nombre, $contenido, $fechaCreacion, $dniCreador);
    }
}

$controlador = new Controlador_aviso_documento();
$controlador->procesarSolicitud();
