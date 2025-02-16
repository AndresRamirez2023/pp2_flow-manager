<?php
require_once __DIR__ . '/../repositories/Repositorio_Usuario.php';
require_once __DIR__ . '/../repositories/Repositorio_Super_Usuario.php';
require_once __DIR__ . '/../classes/Usuario.php';

class Controlador_Sesion
{

	protected $usuario = null;

	protected $super_usuario = null;

	public function login($CorreoElectronico, $clave)
	{

		$r = new Repositorio_Usuario();
		$usuario = $r->login($CorreoElectronico, $clave);

		if ($usuario === false) {
			//fallo el login
			return [false, "Usuario o clave incorrecta"];
		} else {
			//login correcto se ingresa al sistema
			session_start();
			$_SESSION['usuario'] = serialize($usuario);
			return [true, "Ingreso correcto"];
		}
	}

	public function loginInterno($username, $password)
	{
		$r = new Repositorio_Super_Usuario();
		$super_usuario = $r->login($username, $password);

		if ($super_usuario === false) {
			//fallo el login
			return [false, "Usuario o clave incorrecta"];
		} else {
			//login correcto se ingresa al sistema
			session_start();
			$_SESSION['super_user'] = serialize($super_usuario);
			return [true, "Ingreso correcto"];
		}
	}
}
