<?php
require_once 'Departamento.php';
require_once 'Empresa.php';

class Usuario
{

    // DNI (Clave primaria)
    protected $dni;
    // Nombre y apellido
    protected $nombreApellido;
    // Correo Electrónico
    protected $correoElectronico;
    // Fecha de Nacimiento
    protected $fechaNac;
    // Dirección de domicilio
    protected $domicilio;
    // Teléfono
    protected $telefono;
    // Tipo de usuario (Director, Recursos Humanos, Empleado)
    protected $tipoUsuario; // TODO: Usar Enums para definir tipos
    protected $departamento;

    public function __construct(
        $dni,
        $correoElectronico,
        $tipoUsuario,
        ?Departamento $departamento = null,
        $nombreApellido = null,
        $fechaNac = null,
        $domicilio = null,
        $telefono = null
    ) {
        $this->dni = $dni;
        $this->correoElectronico = $correoElectronico;
        $this->tipoUsuario = $tipoUsuario;
        $this->nombreApellido = $nombreApellido;
        $this->fechaNac = $fechaNac;
        $this->domicilio = $domicilio;
        $this->telefono = $telefono;
        $this->departamento = $departamento;
    }

    // Getters
    public function getDni()
    {
        return $this->dni;
    }

    public function getNombreApellido()
    {
        return $this->nombreApellido;
    }

    public function getFechaNac()
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

    public function getCorreoElectronico()
    {
        return $this->correoElectronico;
    }

    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }

    // Validar si el usuario puede solicitar días
    public function puedeSolicitarDias()
    {
        return strtolower($this->tipoUsuario) === 'empleado';
    }

    public function esRRHH()
    {
        $nombre_departamento = $this->departamento->getNombre();
        $nombre = strstr($nombre_departamento, '_') ? substr(strstr($nombre_departamento, '_'), 1) : $nombre_departamento;
        return strtolower($nombre) === 'recursos humanos'; // Validar si el tipo de usuario es 'rrhh'
    }

    public function esEmpleado()
    {
        return strtolower($this->tipoUsuario) === 'empleado'; // Validar si el tipo de usuario es 'empleado'
    }

    public function esDirectivo()
    {
        return strtolower($this->tipoUsuario) === 'directivo'; // Validar si el tipo de usuario es 'director'
    }

    // SETTERS
    public function setNombreApellido($nombreApellido)
    {
        $this->nombreApellido = $nombreApellido;
    }

    public function setFechaNac($fechaNacimiento)
    {
        $this->fechaNac = $fechaNacimiento;
    }

    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;
    }

    public function setCorreoElectronico($correoElectronico)
    {
        $this->correoElectronico = $correoElectronico;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function setTipoUsuario($tipoUsuario)
    {
        $this->tipoUsuario = $tipoUsuario;
    }

    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    public function setDni($dni)
    {
        $this->dni = $dni;
    }
}
