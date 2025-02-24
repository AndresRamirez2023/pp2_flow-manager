<?php
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../repositories/Repositorio_Mensaje.php';
require_once __DIR__ . '/../classes/Mensaje.php';
require_once __DIR__ . '/../classes/archivo.php';
require_once __DIR__ . '/../repositories/Repositorio_Archivo.php';

class Controlador_Mensaje
{
    protected $repositorioMensaje;
    protected $repositorioArchivo;

    public function __construct()
    {
        $this->repositorioMensaje = new Repositorio_Mensaje();
        $this->repositorioArchivo = new Repositorio_Archivo();
    }

    public function redactarMensaje($titulo, $dniRemitente, $tipo, $mensaje, $dniReceptor, $archivo = null)
    {
        $fechaHora = date('Y-m-d H:i:s');
        $resultado = $this->repositorioMensaje->redactar_mensaje(
            $fechaHora, 
            $titulo, 
            $dniRemitente, 
            $tipo, 
            $mensaje, 
            $dniReceptor, 
            $archivo
        );

        if ($resultado && $tipo === "Documentacion" && $archivo) {
            return $this->guardarArchivo($archivo, $fechaHora, $dniRemitente);
        }

        return $resultado;
    }

    public function guardarArchivo($archivo, $fechaHora, $dniRemitente)
    {
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = basename($archivo['name']);
            $contenidoArchivo = file_get_contents($archivo['tmp_name']);

            return $this->repositorioArchivo->guardarArchivo($nombreArchivo, $fechaHora, $contenidoArchivo, $dniRemitente);
        }

        return false;
    }

    public function UpdateMensajesArchivo($DniReceptor, $nuevoTitulo, $nuevoTipo, $nuevoCuerpo , $Contenido=null, $dniCreador=null){
        $this->repositorioMensaje->UpdateMensajes($DniReceptor, $nuevoTitulo, $nuevoTipo, $nuevoCuerpo);

        if (!empty($Contenido) && !empty($dniCreador)){
            $this->repositorioArchivo->UpdateArchivo($Contenido, $dniCreador);
        }
        return true; 
    }

    public function DeleteMensajes($TituloMensaje, $DniReceptor, $Contenido=null, $dniCreador=null){
        $this->repositorioMensaje->DeleteMensajes($TituloMensaje, $DniReceptor);
        if (!empty($Contenido) && !empty($dniCreador)){
            $this->repositorioArchivo->DeleteArchivo($Contenido, $dniCreador);
        }
        return true; 
    }
}
