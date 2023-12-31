<?php 

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {
    
    public static function login(Router $router){
        $mostrarFormulario = true;
        $usuario = new Usuario();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarLogin();
            if(empty($alertas)){
                $passwordIngresado = $usuario->password;
                $usuario = Usuario::where("email", $usuario->email);
                if($usuario) {
                    if( $usuario->comprobarPassword($passwordIngresado) ) {
                        if(!$usuario->confirmado){
                            Usuario::setAlerta("error", "Hola <span class='datoUsuario'>$usuario->nombre</span>! Aún no has confirmado tu cuenta.");
                            Usuario::setAlerta("error", "Te hemos enviado un mail a <span class='datoUsuario'>$usuario->email</span> el día xxxx/xx/xx.");
                            Usuario::setAlerta("error", "Sigue las instrucciones de ese mail para confirmar tu cuenta.");
                            Usuario::setAlerta("error", "Luego de confirmar tu cuenta podrás iniciar sesión.");
                            $mostrarFormulario = false;
                        } else {
                            $_SESSION["id"] = $usuario->id;
                            $_SESSION["nombre"] = $usuario->nombre;
                            $_SESSION["email"] = $usuario->email;
                            $_SESSION["login"] = true;
                            header("Location: /dashboard");
                        }
                    } else {
                        Usuario::setAlerta("error", "Credenciales inválidas");
                        $usuario->email = '';
                    }
                } else 
                    Usuario::setAlerta("error","Credenciales inválidas");
            } 
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/login", [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas,
            'usuario' => $usuario,
            'mostrarFormulario' => $mostrarFormulario 
        ]);
    }
    
    public static function logout(){
        session_destroy();
        header('Location: /');
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
        $mostrarFormulario = true;
        $email = "";
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = new Usuario($_POST);
            $email = $usuario->email;
            $alertas = $usuario->validarEmail();
            if(empty($alertas)) {
                $usuario = Usuario::where("email", $usuario->email);
                if($usuario){
                    if($usuario->confirmado) {
                        if($usuario->token){
                            Usuario::setAlerta("error", "Hola <span class='datoUsuario'>$usuario->nombre</span>! Ya solicitaste reestablecer tu contraseña.");
                            Usuario::setAlerta("error", "Te hemos enviado un mail a <span class='datoUsuario'>$usuario->email</span> el día xxxx/xx/xx para que puedas hacerlo.");
                            Usuario::setAlerta("error", "Sigue las instrucciones de ese mail para crear una nueva.");
                            $mostrarFormulario = false;
                        } else {
                            $usuario->crearToken();
                            $resultado = $usuario->guardar();
                            $email = new Email(
                                $usuario->email,
                                $usuario->nombre,
                                $usuario->token
                            );
                            $email->enviarInstrucciones();
                            if($resultado){
                                Usuario::setAlerta("exito", "Hola $usuario->nombre! Te enviamos un email con las instrucciones para reestablecer tu password.");
                                $mostrarFormulario = false;
                            }
                        }
                    } else {
                        Usuario::setAlerta("error", "Hola <span class='datoUsuario'>$usuario->nombre</span>! Aún no has confirmado tu cuenta.");
                        Usuario::setAlerta("error", "Te hemos enviado un mail a <span class='datoUsuario'>$usuario->email</span> el día xxxx/xx/xx.");
                        Usuario::setAlerta("error", "Sigue las instrucciones de ese mail para confirmar tu cuenta.");
                        Usuario::setAlerta("error", "Luego de confirmar tu cuenta podrás generar una nueva contraseña.");
                        $mostrarFormulario = false;
                    }
                } else {
                    Usuario::setAlerta("error", "El mail ingresado no pertence a ningún usuario registrado");
                }        
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render("auth/olvide", [
            'titulo' => 'Recuperar contraseña',
            'mostrarFormulario' => $mostrarFormulario,
            'email' => $email,
            'alertas' => $alertas
        ]);
    }
    
    public static function reestablecer(Router $router){
        $alertas = [];
        $mostrarFormulario = true;
        if(!isset($_GET['token'])) 
            header('Location: /');

        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token); 
        
        if(empty($usuario))
            header('Location: /');

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if(empty($alertas)) {
                $usuario->token = '';
                $usuario->hashPassword();
                $resultado = $usuario->guardar();
                if($resultado) {
                    $usuario::setAlerta("exito","Hola $usuario->nombre! Tu contraseña ha sido modificada. Ya puedes iniciar sesión");
                    $mostrarFormulario = false;
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render("auth/reestablecer", [
            'titulo' => 'Reestablecer contraseña',
            'alertas' => $alertas,
            'mostrarFormulario' => $mostrarFormulario 
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