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
    protected $CorreoElectronico;
    // Fecha de Nacimiento
    protected $fechaNac;
    // Dirección de domicilio
    protected $domicilio;
    // Teléfono
    protected $telefono;
    // Tipo de usuario (Director, Recursos Humanos, Empleado)
    protected $tipoUsuario; // TODO: Usar Enums para definir tipos
    // Nombre del departamento al que pertenece
    protected $departamento; // TODO: Ser un objeto de tipo Departamento
    // Clave de usuario (almacenada como hash)
    protected $clave;

    public function __construct(
        $Dni,
        $nombre,
        $apellido,
        $fechaNac,
        $domicilio,
        $CorreoElectronico,
        $telefono,
        $tipoUsuario,
        ?Departamento $departamento,
        $clave
    ) {
        $this->Dni = $Dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->fechaNac = $fechaNac;
        $this->domicilio = $domicilio;
        $this->telefono = $telefono;
        $this->CorreoElectronico = $CorreoElectronico;
        $this->tipoUsuario = $tipoUsuario;
        $this->departamento = $departamento;
        $this->clave = password_hash($clave, PASSWORD_DEFAULT); // Guardar clave como hash
    }

    // Getters
    public function getDni()
    {
        return $this->Dni;
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
        return $this->CorreoElectronico;
    }

    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    public function getDepartamento()
    {
        // Si el departamento es un objeto, devolver su nombre, sino 'Sin Departamento'
        return $this->departamento ? $this->departamento->getNombre() : 'Sin Departamento';
    }

    public function getClave()
    {
        return $this->clave;
    }

    // Métodos para manejar la clave
    public function setClave($nuevaClave)
    {
        $this->clave = password_hash($nuevaClave, PASSWORD_DEFAULT); // Actualizar la clave como hash
    }

    public function validarClave($claveIngresada)
    {
        return password_verify($claveIngresada, $this->clave); // Verificar clave ingresada con el hash
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

    public function setDomicilio($direccion)
    {
        $this->domicilio = $direccion;
    }

    public function setCorreoElectronico($CorreoElectronico)
    {
        $this->CorreoElectronico = $CorreoElectronico;
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
        $this->Dni = $dni;
    }
}
