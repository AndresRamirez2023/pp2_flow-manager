<?php
require_once 'Departamento.php';
require_once 'Empresa.php';

class Usuario
{

    // DNI (Clave primaria)
    protected $dni;
    // Nombre
    protected $nombre;
    // Apellido
    protected $apellido;
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
    // Nombre de la empresa al que pertenece
    protected $empresa;
    protected $departamento; // TODO: Ser un objeto de tipo Departamento

    public function __construct(
        $dni,
        $correoElectronico,
        $tipoUsuario,
        ?Departamento $departamento = null,
        $nombre = null,
        $apellido = null,
        $fechaNac = null,
        $domicilio = null,
        $telefono = null
    ) {
        $this->dni = $dni;
        $this->correoElectronico = $correoElectronico;
        $this->tipoUsuario = $tipoUsuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
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

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
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

    public function getEmpresa()
    {
        // devuelve la empresa
       $this->empresa->getNombre();
    }


    public function getDepartamento()
    {
        // Si el departamento es un objeto, devolver su nombre, sino 'Sin asignar'
        // TODO: Cambiar por el objeto departamento
        return $this->departamento ? $this->departamento->getNombre() : 'Sin asignar';
    }

    // Validar si el usuario puede solicitar días
    public function puedeSolicitarDias()
    {
        return strtolower($this->tipoUsuario) === 'empleado';
    }

    public function esRRHH()
    {
        return strtolower($this->tipoUsuario) === 'rrhh'; // Validar si el tipo de usuario es 'rrhh'
    }

    public function esEmpleado()
    {
        return strtolower($this->tipoUsuario) === 'empleado'; // Validar si el tipo de usuario es 'rrhh'
    }

    public function esDirectivo()
    {
        return strtolower($this->tipoUsuario) === 'directivo'; // Validar si el tipo de usuario es 'director'
    }

    // SETTERS
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
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

    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
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
