<?php

class Archivo {
    private $nombreArchivo;
    private $contenido;
    private $fechaCreacion;
    private $dniCreador;

    public function __construct($nombreArchivo, $contenido, $fechaCreacion, $dniCreador)
    {
        $this->nombreArchivo = $nombreArchivo;
        $this->contenido = $contenido;
        $this->fechaCreacion = $fechaCreacion;
        $this->dniCreador = $dniCreador;
    }

    public function getNombreArchivo() {
        return $this->nombreArchivo;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getDnicreador() {
        return $this->dniCreador;
    }
}
