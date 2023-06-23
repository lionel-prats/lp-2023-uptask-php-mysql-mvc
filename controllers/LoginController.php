<?php 

namespace Controllers;

use MVC\Router;
use Model\Usuario;

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
        $usuario = new Usuario;
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)) {

            }
        }
        $router->render("auth/crear", [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }
    
    public static function olvide(Router $router){
        if($_SERVER["REQUEST_METHOD"]){

        }
        $router->render("auth/olvide", [
            'titulo' => 'Recuperar contraseña'
        ]);
    }
    
    public static function reestablecer(Router $router){
        if($_SERVER["REQUEST_METHOD"]){

        }
        $router->render("auth/reestablecer", [
            'titulo' => 'Reestablecer contraseña'
        ]);
    }

    public static function mensaje(Router $router){
        $router->render("auth/mensaje", [
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }

    public static function confirmar(Router $router){
        $router->render("auth/confirmar", [
            'titulo' => 'Confirma tu cuenta UpTask'
        ]);
    }

}