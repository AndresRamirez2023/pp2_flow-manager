<?php
require_once __DIR__ . '../../repositories/Repositorio_Empresa.php';

class Controlador_Empresa
{
    protected $re;

    public function __construct()
    {
        $this->re = new Repositorio_Empresa();
    }

    public function get_all()
    {
        return $this->re->get_all();
    }

    public function get_by_name($nombre)
    {
        return $this->re->get_by_name($nombre);
    }

    public function create(Empresa $empresa)
    {
        return $this->re->create($empresa);
    }

    public function update(Empresa $empresa)
    {
        return $this->re->update($empresa);
    }

    public function delete($nombre)
    {
        return $this->re->delete($nombre);
    }
}
