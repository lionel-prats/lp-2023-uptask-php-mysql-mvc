<?php 

namespace Controllers;

use Model\Tarea;

class TareaController {

    public static function index() {

    }
    
    public static function crear() {
        $tarea = new Tarea($_POST);
        echo json_encode($tarea);
    }
    
    public static function actualizar() {

    }

    public static function eliminar() {

    }
}