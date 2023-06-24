<?php 

namespace Controllers;

use MVC\Router;
use Classes\Email;
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
                $existeUsuario = Usuario::where("email", $usuario->email);
                if($existeUsuario) {
                    Usuario::setAlerta("error","EL mail ingresado ya se encuentra registrado");
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->crearToken();
                    $resultado = $usuario->guardar();
                    $email = new Email(
                        $usuario->email,
                        $usuario->nombre,
                        $usuario->token
                    );
                    $email->enviarConfirmacion();
                    if($resultado) 
                        header('Location: /mensaje');
                } 
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
        $alertas = [];
        if(!isset($_GET['email']) || !isset($_GET['token'])) 
            header('Location: /');
        elseif(!filter_var(s($_GET['email']), FILTER_VALIDATE_EMAIL))
            header('Location: /');
        $email = s($_GET['email']);
        $usuario = Usuario::where('email', $email); 
        if(empty($usuario))
            header('Location: /');
        elseif($usuario->token != $_GET['token'])
            header('Location: /');
        else {
            $usuario->confirmado = "1";
            unset($usuario->password2);
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta("exito", "Tu cuenta ha sido confirmada. Ya puedes iniciar sesión.");
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/confirmar", [
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas 
        ]);
    }

}