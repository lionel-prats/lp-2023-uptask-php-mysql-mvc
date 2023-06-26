<?php 

namespace Controllers;

use MVC\Router;

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