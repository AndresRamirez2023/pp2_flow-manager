<?php

class Mensaje
{
    protected $fechaHora;
    protected $tituloMensaje;
    protected $remitente;
    protected $tipoMensaje;
    protected $cuerpoMensaje;
    protected $receptor;
    protected $requiereFirma;
    protected $pathArchivo;


    public function __construct($fechaHora, $tituloMensaje, $remitente, $tipoMensaje, $cuerpoMensaje, $receptor, $requiereFirma, $pathArchivo)
    {

        $this->fechaHora = $fechaHora;
        $this->tituloMensaje = $tituloMensaje;
        $this->remitente = $remitente;
        $this->tipoMensaje = $tipoMensaje;
        $this->cuerpoMensaje = $cuerpoMensaje;
        $this->receptor = $receptor;
        $this->requiereFirma = $requiereFirma;
        $this->pathArchivo = $pathArchivo;
    }

    public function getFechaHora()
    {
        return $this->fechaHora;
    }

    public function getTituloMensaje()
    {
        return $this->tituloMensaje;
    }

    public function getRemitente()
    {
        return $this->remitente;
    }

    public function getTipoMensaje()
    {
        return $this->tipoMensaje;
    }

    public function getCuerpoMensaje()
    {
        return $this->cuerpoMensaje;
    }

    public function getReceptor()
    {
        return $this->receptor;
    }

    public function getRequiereFirma()
    {
        return $this->requiereFirma;
    }

    public function getPathArchivo()
    {
        return $this->pathArchivo;
    }
}
