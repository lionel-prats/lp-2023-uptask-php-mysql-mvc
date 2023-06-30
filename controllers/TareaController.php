<?php 

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class TareaController {

    public static function index() {

    }
    
    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];
                echo json_encode($respuesta);
            } else {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea agregada correctamente'
                ];
                echo json_encode($respuesta);
            }
        }
    }
    
    public static function actualizar() {

    }

    public static function eliminar() {

    }
}