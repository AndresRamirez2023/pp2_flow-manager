<?php
require_once 'Departamento.php';
class Usuario
{
    // DNI (Clave primaria)
    protected $Dni;
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
    public function __construct($Dni, $nombre, $apellido, $fechaNac, $domicilio, $email, $telefono, $tipoUsuario, ?Departamento $departamento, $clave)
    {
        $this->Dni = $Dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->fechaNac = $fechaNac;
        $this->domicilio = $domicilio;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->tipoUsuario = $tipoUsuario;
        $this->departamento = $departamento;

    }


    public function getDni()
    {
        return $this->Dni;
    }

    public function setId($Dni)
    {
        $this->Dni = $Dni;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }

    public function getfechaNac()
    {
        return $this->fechaNac;
    }


    public function getDomicilio()
    {
        return $this->domicilio;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    public function getDepartamento()
    {
        return $this->departamento ? $this->departamento->getNombre() : 'Sin Departamento';
    }





}
