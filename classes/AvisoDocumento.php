<?php
class AvisoDocumento {
    private $tipoEnvio;
    private $usuarioId;
    private $titulo;
    private $cuerpo;
    private $departamentos;
    private $usuarioDocumento;
    private $archivo;
    private $nombreArchivo;
    private $mensaje;
    private $requiereFirma;

    // Métodos getters y setters para las propiedades

    public function setTipoEnvio($tipoEnvio) {
        $this->tipoEnvio = $tipoEnvio;
    }

    public function getTipoEnvio() {
        return $this->tipoEnvio; // Devuelve el valor de tipoEnvio
    }

    public function setUsuarioId($usuarioId) {
        $this->usuarioId = $usuarioId;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setCuerpo($cuerpo) {
        $this->cuerpo = $cuerpo;
    }

    public function setDepartamentos($departamentos) {
        $this->departamentos = $departamentos;
    }

    public function setUsuarioDocumento($usuarioDocumento) {
        $this->usuarioDocumento = $usuarioDocumento;
    }

    public function setArchivo($archivo) {
        $this->archivo = $archivo;
    }

    public function setNombreArchivo($nombreArchivo) {
        $this->nombreArchivo = $nombreArchivo;
    }

    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }

    public function setRequiereFirma($requiereFirma) {
        $this->requiereFirma = $requiereFirma;
    }

    public function getArchivo() {
        return $this->archivo;  // Devuelve el objeto Archivo relacionado
    }

    // Otros métodos necesarios...
}
