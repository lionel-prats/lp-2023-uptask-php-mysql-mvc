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
                return;
            } /* else {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea agregada correctamente'
                ];
                echo json_encode($respuesta);
            } */
            
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada correctamente'
            ];
            echo json_encode($respuesta);
        }
    }
    
    public static function actualizar() {

    }

    public static function eliminar() {

    }
}