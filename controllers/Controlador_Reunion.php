<?php


require_once __DIR__ . '../../repositories/Repositorio_Reunion.php';


class Controlador_Reunion{

    protected $reunion;


    public function __construct()
    {
        $this->reunion= new Repositorio_Reunion();
    }

    public function get_all($dato)
    {
        return $this->reunion->getAll($dato);
    }

    public function update(Reunion $reunion, $usuario)
    {
        return $this->reunion->update($reunion, $usuario);
    }

    public function create(Reunion $reunion){
        return $this->reunion->create($reunion);
    }

    public function delete($dato){
        return $this->reunion->delete($dato);
    }







}









