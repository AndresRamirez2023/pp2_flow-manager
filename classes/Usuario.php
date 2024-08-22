<?php
class Usuario
{
    // DNI (Clave primaria)
    protected $DNI;
    protected $nombre_usuario;
    // Nombre
    protected $nombre;
    // Apellido
    protected $apellido;
    // Correo Electrónico
    protected $email;
    // Fecha de Nacimiento
    protected $fechaNac;
    // Dirección de domicilio
    protected $domicilio;
    // Teléfono
    protected $telefono;
    // Tipo de usuario (Director, Recursos humanos, empleado)
    protected $tipoUsuario; // TODO: Debe usar Enums de tipos
    // Nombre del departamento al que pertenece
    protected $departamento; // TODO: Debe ser un ojeto de tipo Departamento 
    public function __construct($nombre_usuario, $nombre, $apellido, $email, $id = null)
    {
        $this->nombre_usuario = $nombre_usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->id = $id;
    }

    public function getUsuario()
    {
        return "$this->nombre_usuario";
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setId($DNI)
    {
        $this->DNI = $DNI;
    }
    public function getId()
    {
        return $this->DNI;
    }


}
