<?php
class Archivo
{
    // TODO: Atributos:
    // Tipo de Documento (Clave primaria)
    // Fecha de creación (Clave primaria)
    // Contenido o datos
    // Nombre del archivo

    // TODO: Getters y setters

    protected $tipoArchivo;
    protected $nombre;
    protected $fechaCreacion;
    protected $contenido;


    public function __construct($tipoArchivo, $nombre, $fechaCreacion, $contenido)
    {
        $this->tipoArchivo=$tipoArchivo;
        $this->nombre=$nombre;
        $this->fechaCreacion=$fechaCreacion;
        $this->contenido=$contenido;

    }
        public function getTipoArchivo()
        {
            return "$this->tipoArchivo";
        }

        public function getNombre()
        {
            return "$this->nombre";
        }


        public function fechaCreacion()
        {
            return "$this->fechaCreacion";
        }

        public function contenido()
        {
            return "$this->contenido()";
        }

    }
