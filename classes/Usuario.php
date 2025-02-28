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
    protected $tipo_de_usuario; // TODO: Usar Enums para definir tipos
    // Nombre de la empresa al que pertenece
    protected $empresa;
    protected $departamento; // TODO: Ser un objeto de tipo Departamento

    public function __construct(
        $dni,
        $correoElectronico,
        $tipo_de_usuario,
        ?Departamento $departamento = null,
        $nombreApellido = null,
        $fechaNac = null,
        $domicilio = null,
        $telefono = null
    ) {
        $this->dni = $dni;
        $this->correoElectronico = $correoElectronico;
        $this->tipo_de_usuario = $tipo_de_usuario;
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
        return $this->tipo_de_usuario;
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
        return strtolower($this->tipo_de_usuario) === 'empleado';
    }

    public function esRRHH()
    {
        return strtolower(trim($this->tipo_de_usuario)) === 'rrhh';
    }

    public function esEmpleado()
    {
        return strtolower($this->tipo_de_usuario) === 'empleado'; // Validar si el tipo de usuario es 'rrhh'
    }

    public function esDirectivo()
    {
        return strtolower($this->tipo_de_usuario) === 'directivo'; // Validar si el tipo de usuario es 'director'
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

    public function setTipoUsuario($tipo_de_usuario)
    {
        $this->tipo_de_usuario = $tipo_de_usuario;
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
