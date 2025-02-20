<?php
require_once __DIR__ . '/../repositories/Repositorio_Usuario.php';
require_once __DIR__ . '/../repositories/Repositorio_Super_Usuario.php';
require_once __DIR__ . '/../classes/Usuario.php';

class Controlador_Sesion
{
	protected $usuario = null;
	protected $super_usuario = null;
	protected $ru;
	protected $rsu;

	public function __construct()
	{
		$this->ru = new Repositorio_Usuario();
		$this->rsu = new Repositorio_Super_Usuario();
	}

	public function login($CorreoElectronico, $clave)
	{
		$usuario = $this->ru->login($CorreoElectronico, $clave);

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
		$super_usuario = $this->rsu->login($username, $password);

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
