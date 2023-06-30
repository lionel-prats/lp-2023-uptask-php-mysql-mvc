<?php 

namespace Controllers;

use Model\Tarea;

class TareaController {

    public static function index() {

    }
    
    public static function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $respuesta = [
                'proyectoId' => $_POST['proyectoId']
            ];
            echo json_encode($respuesta);
        }
    }
    
    public static function actualizar() {

    }

    public static function eliminar() {

    }
}