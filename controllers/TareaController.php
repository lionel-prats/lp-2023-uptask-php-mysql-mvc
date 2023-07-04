<?php 

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class TareaController {

    public static function index() {
        $proyectoId = s($_GET['id']);
        if(!$proyectoId)
            header('Location: /dashboard');
        $proyecto = Proyecto::where('url', $proyectoId); 
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id'])
            header('Location: /404');
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        echo json_encode($tareas);
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
            } 
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