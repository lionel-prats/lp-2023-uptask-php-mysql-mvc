<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;
    public $password_actual;
    public $password_nuevo;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? '0';
    }
    public function validarLogin() {
        if(!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El formato del email ingresado es incorrecto";
        } elseif( !$this->password || strlen($this->password) < 6 ) {
            self::$alertas["error"][] = "Credenciales inválidas";
            $this->email = '';
        } 
        return self::$alertas;
    }
    // validador formulario de creacion de cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas["error"][] = "El nombre es obligatorio"; 
        }
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El email ingresado no es válido";
        }
        if((!$this->password || strlen($this->password) < 6) || (!$this->password2 || strlen($this->password2) < 6)) {
            self::$alertas["error"][] = "El password es obligatorio para ambos campos, de una longitud mínima de 6 caracteres y deben coincidir";
        } elseif($this->password !== $this->password2) {
            self::$alertas["error"][] = "Los passwords ingresados no coinciden";
        }
        return self::$alertas;
    }
    
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El email ingresado no es válido";
        }
        return self::$alertas; 
    }
    public function validarPassword() {
        if( (!$this->password || strlen($this->password) < 6) || (!$this->password2 || strlen($this->password2) < 6) ) {
            self::$alertas["error"][] = "El password es obligatorio para ambos campos, de una longitud mínima de 6 caracteres y deben coincidir";
        } elseif($this->password !== $this->password2) {
            self::$alertas["error"][] = "Los passwords ingresados no coinciden";
        }
        return self::$alertas; 
    }

    public function validar_perfil() {
        if(!$this->nombre) {
            self::$alertas["error"][] = "El nombre es obligatorio";
        } 
        $this->validarEmail();
        return self::$alertas; 
    }

    public function nuevo_password() : array {
        if( !$this->password_actual || !$this->password_nuevo || strlen($this->password_actual) < 6 || strlen($this->password_nuevo) < 6 ) {
            self::$alertas["error"][] = "Todos los campos son obligatorios y de un mínimo de 6 caracteres";
        } 
        return self::$alertas; 
    }

    // hashear password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        return;
    }
    // token para creacion de cuenta
    public function creartoken() : void {
        $this->token = md5(uniqid());
        return;
    }
    public function comprobarPassword($password) : bool { 
        $resultado = password_verify($password, $this->password);
        return $resultado;
    }
}