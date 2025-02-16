<?php
class Controlador_Usuario
{
    protected $ru;

    public function __construct()
    {
        $this->ru = new Repositorio_Usuario();
    }

    public function get_all() {}

    public function get_by_dni($nombre) {}

    public function login($CorreoElectronico, $clave)
    {
        $this->ru->login($CorreoElectronico, $clave);
    }

    public function create(Usuario $usuario, $nombreApellido, $clave)
    {
        // Validar que el DNI sea exactamente de 8 caracteres y numérico
        if (!preg_match('/^\d{8}$/', $usuario->getDni())) {
            die("DNI no válido. Debe contener exactamente 8 dígitos.");
        }

        // Separar nombre y apellido
        $nombreApellidoArray = explode(" ", $nombreApellido, 2);
        $nombre = $nombreApellidoArray[0];
        $apellido = isset($nombreApellidoArray[1]) ? $nombreApellidoArray[1] : "";

        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);

        return $this->ru->save($usuario, $clave);
    }

    public function update(Usuario $usuario) {}

    public function delete(Usuario $usuario) {}
}
