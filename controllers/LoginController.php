<?php 

namespace Controllers;

use MVC\Router;

class LoginController {
    
    public static function login(Router $router){
        if($_SERVER["REQUEST_METHOD"]){

        }
        $router->render("auth/login", [
            'titulo' => 'Iniciar Sesión'
        ]);
    }
    
    public static function logout(){
        echo "Desde Logout";
    }
    
    public static function crear(Router $router){
        if($_SERVER["REQUEST_METHOD"]){

        }
        $router->render("auth/crear", [
            'titulo' => 'Crear Cuenta'
        ]);
    }
    
    public static function olvide(Router $router){
        if($_SERVER["REQUEST_METHOD"]){

        }
        $router->render("auth/olvide", [
            'titulo' => 'Reestablecer contraseña'
        ]);
    }
    
    public static function reestablecer(){
        echo "Desde Reestablecer";
        if($_SERVER["REQUEST_METHOD"]){

        }
    }

    public static function mensaje(){
        echo "Desde Mensaje";
    }

    public static function confirmar(){
        echo "Desde Confirmar";
    }

}