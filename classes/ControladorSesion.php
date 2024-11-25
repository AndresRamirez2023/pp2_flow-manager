<?php
require_once __DIR__ . '/../repositories/Repositorio_Usuario.php';
require_once 'Usuario.php';

class Controlador_Sesion
{

	protected $usuario = null;

	public function login($CorreoElectronico, $clave)
	{

		$r = new Repositorio_Usuario();
		$usuario = $r->login($CorreoElectronico, $clave);

		if ($usuario === false) {
			//fallo el login
			return [false, "usuario o clave incorrecta"];
		} else {
			//login correcto se ingresa al sistema
			session_start();
			$_SESSION['usuario'] = serialize($usuario);
			return [true, "Ingreso correcto"];
		}
	}
}