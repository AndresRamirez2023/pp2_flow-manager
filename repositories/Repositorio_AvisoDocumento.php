<?php

require_once 'Repositorio.php';
require_once __DIR__ . '/../classes/AvisoDocumento.php';
require_once __DIR__ . '/../classes/Archivo.php';

class Repositorio_AvisoDocumento extends Repositorio
{
    // Constructor para inicializar la conexión
    public function __construct()
    {
        parent::__construct();
    }

    // Guardar un aviso o un documento
    public function guardar(AvisoDocumento $AvisoDocumento)
    {
        // Verificar si el tipo de envío es válido
        $tipoEnvio = $AvisoDocumento->getTipoEnvio(); // Obtenemos el tipo de envío desde el objeto AvisoDocumento
    
        // Usar los métodos del objeto AvisoDocumento para obtener el archivo
        $archivo = $AvisoDocumento->getArchivo();  // Obtener el archivo que se asocia a este aviso o documento
    
        if ($tipoEnvio === 'aviso') {
            return $this->guardarAviso($archivo);  // Pasamos el archivo relacionado con el aviso
        } elseif ($tipoEnvio === 'documento') {
            return $this->guardarDocumento($archivo);  // Pasamos el archivo relacionado con el documento
        }
    
        throw new Exception('Tipo de envío inválido.');
    }

    // Guardar un aviso en la base de datos
    /*//private function guardarAviso(Archivo $aviso)
    {
        $sql = "INSERT INTO avisos (titulo, cuerpo, departamentos, usuario_id) VALUES (?, ?, ?, ?)";
        $query = self::$conexion->prepare($sql);

        if (!$query) {
            throw new Exception('Error al preparar la consulta: ' . self::$conexion->error);
        }

        $titulo = $aviso->getNombreArchivo(); // Usando el nombre como título
        $cuerpo = $aviso->getContenido(); // Usando el contenido
        $departamentos = implode(',', $aviso->getDepartamentos()); // Asumiendo que hay un método getDepartamentos
        $usuario_id = $aviso->getDnicreador();

        $query->bind_param("sssi", $titulo, $cuerpo, $departamentos, $usuario_id);

        return $query->execute();
    }*///

    // Guardar un documento en la base de datos
    private function guardarDocumento(Archivo $archivo)
    {
        $sql = "INSERT INTO archivos (nombre, FechaCreacion, Contenido, DniCreador) VALUES (?, ?, ?, ?)";
        $query = self::$conexion->prepare($sql);

        if (!$query) {
            throw new Exception('Error al preparar la consulta: ' . self::$conexion->error);
        }

        $nombre = $archivo->getNombreArchivo();
        $fechaCreacion = $archivo->getFechaCreacion();
        $contenido = $archivo->getContenido();
        $dniCreador = $archivo->getDnicreador();

        $query->bind_param("sbsi", $nombre, $fechaCreacion, $contenido, $dniCreador);

        return $query->execute();
    }
}

