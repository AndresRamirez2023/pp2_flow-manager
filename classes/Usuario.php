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
    // Tipo de usuario (Director, Recursos humanos, empleado)
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
    // Métodos para manejar la clave
    public function setClave($nuevaClave)
    {
        $this->clave = password_hash($nuevaClave, PASSWORD_DEFAULT); // Actualizar la clave como hash
    }

    public function validarClave($claveIngresada)
    {
        return password_verify($claveIngresada, $this->clave); // Verificar clave ingresada con el hash
    }
}
