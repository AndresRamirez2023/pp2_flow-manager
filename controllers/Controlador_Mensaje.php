<?php
require_once __DIR__ . '/../repositories/Repositorio_Mensaje.php';
require_once __DIR__ . '/../classes/Mensaje.php';

class Controlador_Mensaje
{
    protected $rm;

    public function __construct()
    {
        $this->rm = new Repositorio_Mensaje();
    }

    public function get_all($dni, $tipo)
    {
        return $this->rm->get_all($dni, $tipo);
    }

    public function send(Mensaje $mensaje)
    {
        return $this->rm->send($mensaje);
    }

    public function delete(Mensaje $mensaje)
    {
        // TODO: Borrado lógico de los mensajes seleccionados por un usuario
    }
}
