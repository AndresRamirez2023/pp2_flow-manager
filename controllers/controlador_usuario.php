<?php
require_once __DIR__ . '/../repositories/Repositorio_Usuario.php';

class Controlador_Usuario
{


    protected $ru;

    public function __construct()
    {
        $this->ru = new Repositorio_Usuario();
    }

    public function get_all()
    {
        return $this->ru->get_all();
    }

    public function get_by_param($param)
    {
        if ($param instanceof Departamento) {
            $usuarios = $this->get_all();
            $usuarios_filtrados = [];
            foreach ($usuarios as $usuario) {
                if ($usuario->getDepartamento() != null && $usuario->getDepartamento()->getNombre() == $param->getNombre()) {
                    $usuarios_filtrados[] = $usuario;
                }
            }
            return $usuarios_filtrados;
        } else {
            return $this->ru->get_by_param($param);
        }
    }

    public function save(Usuario $usuario, $clave)
    {
        try {
            $this->validator($usuario, $clave);

            return $this->ru->save($usuario, $clave);
        } catch (Exception $e) {
            $_SESSION['mensaje'] = $e->getMessage();
            $_SESSION['mensaje_tipo'] = "danger";
            return false;
        }
    }

    public function update(Usuario $usuario, $clave)
    {
        try {
            $this->validator($usuario, $clave);

            return $this->ru->update($usuario, $clave);
        } catch (Exception $e) {
            $_SESSION['mensaje'] = $e->getMessage();
            $_SESSION['mensaje_tipo'] = "danger";
            return false;
        }
    }

    public function delete($dni)
    {
        $this->ru->delete($dni);
    }

    private function validator(Usuario $usuario, $clave)
    {
        // **VALIDACIONES BACKEND**
        if ($usuario->getDni()) {
            // Validar si el DNI ya existe en la base de datos
            if ($this->get_by_param($usuario->getDni())) {
                throw new Exception('Error: El DNI ingresado ya está registrado.');
            }
            // Validar DNI (exactamente 8 dígitos)
            if (!preg_match('/^\d{8}$/', $usuario->getDni())) {
                throw new Exception('Error: El DNI debe contener exactamente 8 dígitos numéricos.');
            }
        }

        if ($usuario->getCorreoElectronico()) {
            // Validar si el correo electrónico ya existe en la base de datos
            if ($this->get_by_param($usuario->getCorreoElectronico())) {
                throw new Exception('Error: El correo electrónico ingresado ya está registrado.');
            }

            // Validar correo electrónico
            if (!filter_var($usuario->getCorreoElectronico(), FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Error: El correo electrónico no tiene un formato válido.');
            }
        }

        // Validar nombre y apellido (solo letras y espacios, entre 2 y 50 caracteres)
        if ($usuario->getNombreApellido() && !preg_match('/^[A-Za-zÀ-ÿ\s]{2,100}$/', $usuario->getNombreApellido())) {
            throw new Exception('Error: El nombre y el apellido solo pueden contener letras y espacios.');
        }

        // Validar fecha de nacimiento (formato válido y usuario mayor de edad)
        if ($usuario->getFechaNac()) {
            $fechaActual = new DateTime();
            $fecha_nacimiento = DateTime::createFromFormat('Y-m-d', $usuario->getFechaNac());
            if (!$fecha_nacimiento || $fecha_nacimiento > $fechaActual->modify('-18 years')) {
                throw new Exception('Error: El usuario debe ser mayor de 18 años.');
            }
        }

        // Validar dirección (mínimo 5 caracteres)
        if ($usuario->getDomicilio() && strlen($usuario->getDomicilio()) < 5 || strlen($usuario->getDomicilio()) > 100) {
            throw new Exception('Error: La dirección debe contener entre 5 y 100 caracteres.');
        }

        // Validar teléfono (7-15 dígitos opcionales con "+")
        if ($usuario->getTelefono() && !preg_match('/^\+?\d{7,15}$/', $usuario->getTelefono())) {
            throw new Exception('Error: El número de teléfono debe tener entre 7 y 15 dígitos.');
        }

        // Validar tipo de usuario
        $tiposValidos = ['RRHH', 'Directivo', 'Empleado'];
        if ($usuario->getTipoUsuario() && !in_array($usuario->getTipoUsuario(), $tiposValidos)) {
            throw new Exception('Error: Tipo de usuario inválido.');
        }

        // Validar contraseña (mínimo 8 caracteres, al menos una letra y un número)
        if ($clave && !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&.]{8,}$/', $clave)) {
            throw new Exception('Error: La contraseña debe tener al menos 8 caracteres, incluyendo una letra y un número.');
        }
    }
}
