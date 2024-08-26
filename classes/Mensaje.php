<?php

require_once 'Usuario.php';
class Mensaje
{
    // TODO: Atributos:
    // DNI del Usuario que envía el mensaje (Clave primaria)
    // Tipo de Documento (Clave primaria)
    // Fecha de Emisión (Clave primaria)
    // Tipo de mensaje
    // Cuerpo del mensaje
    // DNI del Usuario que recibe el mensaje

    // TODO: Getters y setters

    //FechaHoraMensaje
    protected $fechaHora;
    //TituloMensaje
    protected $tituloMensaje;
    //Dni Remitente
    protected $dniRemitente;
    //Tipo Mensaje
    protected $tipoMensaje;
    //Cuerpo Mensaje
    protected $cuerpoMensaje;
    //Dni Receptor
    protected $dniReceptor;


    public function __construct($fechaHora, $tituloMensaje, Usuario $dniRemitente, $tipoMensaje, $cuerpoMensaje, Usuario $dniReceptor)
    {
        
        $this->fechaHora=$fechaHora;
        $this->tituloMensaje=$tituloMensaje;
        $this->dniRemitente=$dniRemitente;
        $this->tipoMensaje=$tipoMensaje;
        $this->cuerpoMensaje=$cuerpoMensaje;
        $this->dniReceptor=$dniReceptor;
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
        return $this->dniRemitente->getDni();
    }

    public function getTipoMensaje()
    {
        return $this->tipoMensaje;
    }

    public function getCuerpoMensaje()
    {
        return $this->cuerpoMensaje;
    }

    public function getDniReceptor()
    {
        return $this->dniReceptor->getDni();
    }


    




}
