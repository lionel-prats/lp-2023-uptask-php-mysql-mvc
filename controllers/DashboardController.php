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
}