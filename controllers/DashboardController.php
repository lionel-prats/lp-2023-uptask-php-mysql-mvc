<?php 

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Usuario;

class DashboardController {
    public static function index(Router $router) {
        isAuth();
        $id= $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);
        $router->render("dashboard/index", [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    public static function crear_proyecto(Router $router) {
        isAuth();
        $alertas= [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto= new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                $proyecto->url = md5(uniqid());
                $proyecto->propietarioId = $_SESSION['id'];
                $proyecto->guardar();
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render("dashboard/crear-proyecto", [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }
    public static function proyecto(Router $router) {
        isAuth();
        $token = s($_GET['id']); // token (campo url en la tabla proyectos) del proyecto 
        if(!$token)
            header('Location: /dashboard');
        $proyecto = Proyecto::where('url', $token); 
        if(!$proyecto)
            header('Location: /dashboard');
        if($proyecto->propietarioId !== $_SESSION['id'])
            header('Location: /dashboard');
        $router->render("dashboard/proyecto", [
            'titulo' => $proyecto->proyecto
        ]);
    }
    public static function perfil(Router $router) {
        isAuth();

        $alertas = [];

        $usuario = Usuario::find($_SESSION["id"]);

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();    
            if(empty($alertas)){
                $existeUsuario = Usuario::where("email", $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta("error", "EL mail ingresado ya se encuentra registrado");
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->guardar();
                    $_SESSION["nombre"] = $usuario->nombre;
                    $_SESSION["email"] = $usuario->email;
                    Usuario::setAlerta("exito", "Cambios guardados correctamente");
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $router->render("dashboard/perfil", [
            'titulo' => 'Perfil',
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
    public static function cambiar_password(Router $router) {
        isAuth();

        $alertas = [];

        // $usuario = Usuario::find($_SESSION["id"]);

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $usuario = Usuario::find($_SESSION["id"]); 
            $usuario->sincronizar($_POST); // sincronizo el objeto que representa al usuario logueado, cargando en los atributos $password_actual y $password_actual (CONSTRUCTOR Usuario) los passwords que el cliente cargo en los inputs de actualizar password
            $alertas = $usuario->nuevo_password();
            if(empty($alertas)){
                $resultado = $usuario->comprobarPassword($usuario->password_actual);
                if(!$resultado) {
                    Usuario::setAlerta("error", "Credenciales inválidas");
                    $alertas = Usuario::getAlertas();
                } else {

                    $usuario->password = $usuario->password_nuevo;
                    
                    // elimino propiedades no necesarias
                    unset($usuario->password_actual); 
                    unset($usuario->password_nuevo);
                    
                    $usuario->hashPassword();
                    
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta("exito", "Contraseña modificada exitosamente");
                        $alertas = Usuario::getAlertas();
                    }
                }
            }
        }

        $router->render("dashboard/cambiar-password", [
            'titulo' => 'Cambiar Password',
            // "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
}