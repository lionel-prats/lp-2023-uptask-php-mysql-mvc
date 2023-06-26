<?php 

namespace Controllers;

use MVC\Router;
use Model\Proyecto;

class DashboardController {
    public static function index(Router $router) {
        isAuth();
        $router->render("dashboard/index", [
            'titulo' => 'Proyectos'
        ]);
    }
    public static function crear_proyecto(Router $router) {
        isAuth();
        $alertas= [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto= new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                debuguear($proyecto);
            }
        }

        $router->render("dashboard/crear-proyecto", [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }
    public static function perfil(Router $router) {
        isAuth();
        $router->render("dashboard/perfil", [
            'titulo' => 'Perfil'
        ]);
    }
}