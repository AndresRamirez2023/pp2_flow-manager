<?php
require_once __DIR__ . '../../repositories/Repositorio_Departamento.php';

class Controlador_Departamento
{
    protected $rd;

    public function __construct()
    {
        $this->rd = new Repositorio_Departamento();
    }

    public function get_all()
    {
        return $this->rd->get_all();
    }

    // public function get_by_name($nombre)
    // {
    //     return $this->rd->get_by_name($nombre);
    // }

    public function create(Departamento $departamento)
    {
        return $this->rd->create($departamento);
    }

    public function update(Departamento $departamento)
    {
        return $this->rd->update($departamento);
    }

    public function delete($nombre)
    {
        // TODO: Crear uno nuevo con el nuevo nombre, pasar los usuarios y borrar el viejo
    }
}
